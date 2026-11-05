<?php

namespace Tests\Unit;

use App\Services\RiskAiService;
use Illuminate\Container\Container;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;

class RiskAiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $app = new Container;
        Container::setInstance($app);
        $app->instance('config', new Repository(['risk_ai' => ['mode' => 'demo', 'key' => 'test-placeholder', 'model' => 'test-model'], 'risk_ai_examples' => require __DIR__.'/../../config/risk_ai_examples.php']));
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);
        parent::tearDown();
    }

    public function testRegisterReviewDoesNotInferUnseenRecordsOrControlFailures()
    {
        $result = (new RiskAiService)->registerReview(['total_matching' => 100, 'risks' => [
            ['id' => 'OPE1', 'rating' => 'High', 'owner_assigned' => false, 'response' => ''],
            ['id' => 'OPE2', 'rating' => 'Low', 'owner_assigned' => true, 'response' => 'Accept'],
        ]]);
        $this->assertSame(['reviewed' => 2, 'total_matching' => 100], $result['scope']);
        $this->assertStringContainsString('2 of 100', $result['review']['summary']);
        $this->assertStringContainsString('1 high-rated', $result['review']['summary']);
        $this->assertCount(2, $result['review']['gaps']);
        $this->assertStringContainsString('OPE1', $result['review']['gaps'][0]);
        $this->assertStringNotContainsString('OPE2', implode(' ', $result['review']['gaps']));
    }

    public function testChecklistReviewIdentifiesOnlyMissingFields()
    {
        $result = (new RiskAiService)->review(['concern' => 'Supplier interruption', 'risk_description' => 'Delayed parts may stop production.', 'existing_controls' => 'Safety stock']);
        $this->assertSame('demo', $result['mode']);
        $this->assertTrue($result['requires_review']);
        $this->assertCount(1, $result['review']['gaps']);
        $this->assertStringContainsString('causes', $result['review']['gaps'][0]);
        $this->assertArrayNotHasKey('rating', $result['review']);
    }

    public function testMalformedReviewProviderResponseIsRejected()
    {
        config(['risk_ai.mode' => 'openai']);
        Http::fake(['api.openai.com/*' => Create::promiseFor(new Response(200, [], json_encode(['choices' => [['message' => ['content' => json_encode(['summary' => 'Review', 'gaps' => 'invalid'])]]]])))]);
        $this->expectException(\RuntimeException::class);
        (new RiskAiService)->review(['concern' => 'Supplier interruption']);
    }

    public function testAllNineMatrixCells()
    {
        $expected = [['Low', 'Low', 'Moderate'], ['Low', 'Moderate', 'High'], ['Moderate', 'High', 'High']];
        $service = new RiskAiService;
        for ($likelihood = 1; $likelihood <= 3; $likelihood++) {
            for ($impact = 1; $impact <= 3; $impact++) {
                $result = $service->score($impact, $likelihood);
                $this->assertSame($impact * $likelihood, $result['score']);
                $this->assertSame($expected[$likelihood - 1][$impact - 1], $result['rating']);
            }
        }
    }

    public function testInvalidMatrixInputIsRejected()
    {
        $this->expectException(\InvalidArgumentException::class);
        (new RiskAiService)->score(true, 3);
    }

    public function testDemoIsLabelledAndDoesNotInventExistingControls()
    {
        $result = (new RiskAiService)->draft(['concern' => 'Critical parts rely on a single supplier']);
        $this->assertSame('demo', $result['mode']);
        $this->assertTrue($result['requires_review']);
        $this->assertArrayNotHasKey('existing_controls', $result['draft']);
        $this->assertArrayNotHasKey('impact', $result['draft']);
    }

    public function testGeopoliticalTopicProducesDistinctRisksAndSubstantiveDrafts()
    {
        $service = new RiskAiService;
        $result = $service->suggestions(['concern' => 'geopolitical']);
        $this->assertSame('demo', $result['mode']);
        $this->assertCount(3, $result['suggestions']);
        $names = array_column($result['suggestions'], 'risk_name');
        $this->assertCount(3, array_unique($names));
        foreach ($names as $name) {
            $this->assertNotSame('geopolitical', strtolower($name));
            $draft = $service->draft(['concern' => 'geopolitical', 'selected_risk_name' => $name]);
            $this->assertSame($name, $draft['draft']['risk_name']);
            $this->assertGreaterThan(100, strlen($draft['draft']['risk_description']));
            $this->assertNotEmpty($draft['draft']['proposed_controls']);
        }
    }

    public function testUnknownDemoTopicsDoNotFabricateSuggestions()
    {
        $result = (new RiskAiService)->suggestions(['concern' => 'unrecognized topic']);
        $this->assertSame([], $result['suggestions']);
    }

    public function testDemoCannotSelectAnUnrelatedRisk()
    {
        $this->expectException(\RuntimeException::class);
        (new RiskAiService)->draft(['concern' => 'geopolitical', 'selected_risk_name' => 'Invented option']);
    }

    public function testProviderDraftIsValidatedAndExtraFieldsAreDiscarded()
    {
        config(['risk_ai.mode' => 'openai']);
        $draft = ['risk_name' => 'Supplier interruption', 'risk_description' => 'Parts shortages may stop production.', 'risk_drivers' => ['Single supplier'], 'proposed_controls' => ['Qualify alternatives'], 'questions' => ['How much stock is held?'], 'approved' => true];
        // Laravel 7's Http::response helper calls a removed Guzzle Promise v1 function.
        // Supply the supported v2 promise directly, without modifying dependencies.
        Http::fake(['api.openai.com/*' => Create::promiseFor(new Response(200, [], json_encode(['choices' => [['message' => ['content' => json_encode($draft)]]]])))]);
        $result = (new RiskAiService)->draft(['concern' => 'Single supplier']);
        $this->assertSame('Supplier interruption', $result['draft']['risk_name']);
        $this->assertArrayNotHasKey('approved', $result['draft']);
        Http::assertSent(function ($request) { return $request->url() === 'https://api.openai.com/v1/chat/completions'; });
    }

    public function testLiveSuggestionFormatIsValidated()
    {
        config(['risk_ai.mode' => 'openai']);
        $payload = ['suggestions' => [['risk_name' => 'Border restrictions interrupt material deliveries', 'reason' => 'Relevant to cross-border supply chains.', 'approved' => true]]];
        Http::fake(['api.openai.com/*' => Create::promiseFor(new Response(200, [], json_encode(['choices' => [['message' => ['content' => json_encode($payload)]]]])))]);
        $result = (new RiskAiService)->suggestions(['concern' => 'geopolitical']);
        $this->assertSame('openai', $result['mode']);
        $this->assertSame('Border restrictions interrupt material deliveries', $result['suggestions'][0]['risk_name']);
        $this->assertArrayNotHasKey('approved', $result['suggestions'][0]);
    }

    public function testMalformedProviderSuggestionsAreRejected()
    {
        config(['risk_ai.mode' => 'openai']);
        Http::fake(['api.openai.com/*' => Create::promiseFor(new Response(200, [], json_encode(['choices' => [['message' => ['content' => '{"suggestions":[{"risk_name":"Missing reason"}]}']]]])))]);
        $this->expectException(\RuntimeException::class);
        (new RiskAiService)->suggestions(['concern' => 'geopolitical']);
    }

    public function testMalformedProviderOutputIsRejected()
    {
        config(['risk_ai.mode' => 'openai']);
        Http::fake(['api.openai.com/*' => Create::promiseFor(new Response(200, [], json_encode(['choices' => [['message' => ['content' => '{"risk_name":true}']]]])))]);
        $this->expectException(\RuntimeException::class);
        (new RiskAiService)->draft(['concern' => 'Single supplier']);
    }
}
