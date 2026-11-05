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
        $app->instance('config', new Repository(['risk_ai' => ['mode' => 'demo', 'key' => 'test-placeholder', 'model' => 'test-model']]));
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

    public function testMalformedProviderOutputIsRejected()
    {
        config(['risk_ai.mode' => 'openai']);
        Http::fake(['api.openai.com/*' => Create::promiseFor(new Response(200, [], json_encode(['choices' => [['message' => ['content' => '{"risk_name":true}']]]])))]);
        $this->expectException(\RuntimeException::class);
        (new RiskAiService)->draft(['concern' => 'Single supplier']);
    }
}
