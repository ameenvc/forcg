<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RiskAiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['risk_ai.enabled' => true, 'risk_ai.mode' => 'demo']);
        // Minimal isolated fixtures for existing auth/activity middleware.
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('last_seen')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('app_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meta_key');
            $table->string('meta_value')->nullable();
        });
        Schema::create('error_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('error');
            $table->string('logged_date');
            $table->integer('solved');
            $table->integer('occurence');
        });
    }

    private function signIn()
    {
        $user = new User;
        $user->email = 'reviewer@example.test';
        $user->password = 'test-password-placeholder';
        $user->timestamps = false;
        $user->save();
        $this->actingAs($user);
    }

    public function testAiOptionStaysVisibleAndReflectsServiceAvailability()
    {
        foreach ([false, true] as $enabled) {
            config(['risk_ai.enabled' => $enabled]);
            $html = view('user.risks.ai-trigger')->render().view('user.risks.ai-assist')->render();
            $this->assertStringContainsString('AI Assist', $html);
            $this->assertStringContainsString('aria-controls="risk-ai-panel"', $html);
            if ($enabled) {
                $this->assertStringContainsString('id="risk-ai-generate"', $html);
                $this->assertStringContainsString('Example library', $html);
            } else {
                $this->assertStringContainsString('AI assistance is not available yet.', $html);
                $this->assertStringNotContainsString('id="risk-ai-generate"', $html);
            }
        }
    }

    public function testModernRiskFormPreservesSubmissionFields()
    {
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        view()->share('errors', new \Illuminate\Support\ViewErrorBag);
        $html = view('user.risks.addrisk', [
            'rskgrp' => collect([(object) ['riskgroups' => 'Operational']]),
            'departments' => collect([(object) ['id' => 1, 'departmentname' => 'Maintenance']]),
            'strategy' => collect([(object) ['name' => 'Mitigate']]),
            'hml' => collect(['Low', 'Moderate', 'High'])->map(function ($name) { return (object) ['name' => $name]; }),
            'riskreviewers' => collect(),
        ])->render();
        foreach (['riskgroup', 'riskscr', 'riskarea', 'riskname', 'riskdec', 'riskdri', 'existing_controls', 'risk_response', 'impa', 'like', 'riskreviewer_id'] as $field) {
            $this->assertStringContainsString('name="'.$field.'"', $html);
        }
        $this->assertStringContainsString('/user/risks/submit', $html);
        $this->assertStringContainsString('id="risk-score-card"', $html);
        $this->assertStringContainsString('id="risk-ai-apply-all"', $html);
        if (getenv('RISK_FORM_PREVIEW_PATH')) {
            file_put_contents(getenv('RISK_FORM_PREVIEW_PATH'), $html);
        }
    }

    public function testGuestsCannotRequestDrafts()
    {
        $this->postJson('/user/risks/ai/draft', ['concern' => 'Single supplier for critical parts'])->assertStatus(401);
    }

    public function testAuthenticatedUserCanGenerateDemoDraftWithoutSavingRisk()
    {
        $this->signIn();
        $this->postJson('/user/risks/ai/draft', ['concern' => 'Single supplier for critical parts', 'impact' => 3, 'likelihood' => 2])
            ->assertOk()->assertJson(['mode' => 'demo', 'requires_review' => true, 'assessment' => ['score' => 6, 'rating' => 'High']]);
        $this->assertFalse(Schema::hasTable('risks'));
    }

    public function testTopicCanBeExploredAndSelectedBeforeDrafting()
    {
        $this->signIn();
        $response = $this->postJson('/user/risks/ai/draft', ['action' => 'suggest', 'concern' => 'geopolitical']);
        $response->assertOk()->assertJsonCount(3, 'suggestions');
        $name = $response->json('suggestions.1.risk_name');
        $this->postJson('/user/risks/ai/draft', ['action' => 'draft', 'concern' => 'geopolitical', 'selected_risk_name' => $name])
            ->assertOk()->assertJsonPath('draft.risk_name', $name);
        $this->assertFalse(Schema::hasTable('risks'));
    }

    public function testFeatureFlagDisablesEndpoint()
    {
        $this->signIn();
        config(['risk_ai.enabled' => false]);
        $this->postJson('/user/risks/ai/draft', ['concern' => 'Single supplier for critical parts'])->assertStatus(404);
    }

    public function testConcernIsValidated()
    {
        $this->signIn();
        $this->postJson('/user/risks/ai/draft', ['concern' => 'x'])->assertStatus(422);
    }

    public function testMfaRequirementIsRetained()
    {
        $this->signIn();
        DB::table('app_configurations')->insert(['meta_key' => 'enable_mfa', 'meta_value' => '1']);
        $this->postJson('/user/risks/ai/draft', ['concern' => 'Single supplier for critical parts'])
            ->assertRedirect(route('2fa.setupPage'));
    }

    public function testCsrfIsEnforcedOutsideTestingEnvironment()
    {
        $this->signIn();
        $this->app['env'] = 'local';
        $this->postJson('/user/risks/ai/draft', ['concern' => 'Single supplier for critical parts'])->assertStatus(419);
    }
}
