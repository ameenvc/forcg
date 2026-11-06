<?php
namespace Tests\Feature;

use App\Risk;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RiskRegisterDetailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
        Schema::create('users', function (Blueprint $table) { $table->increments('id'); $table->string('email'); $table->string('name')->nullable(); $table->string('employeeid')->nullable(); $table->string('profile_picture')->nullable(); $table->softDeletes(); });
        DB::table('users')->insert(['id' => 1, 'email' => 'reviewer@example.test']);
        $this->actingAs((new User)->forceFill(['id' => 1, 'email' => 'reviewer@example.test']));
    }

    public function testDetailPreservesAssessmentActionsHistoryCommentsAndEscapesRiskText()
    {
        config(['risk_ai.enabled' => true]);
        $risk = (new Risk)->forceFill(['Rid' => 211, 'grp_id' => 'OPE211', 'riskname' => 'Critical supplier disruption across multiple regions and production sites',
            'riskgroup' => 'Operational', 'riskdec' => '<script>alert(1)</script>Production interruption & delayed deliveries', 'riskdri' => '<img src=x onerror=alert(1)>',
            'existing_controls' => 'Buffer inventory', 'riskscr' => 'Process assessment', 'impa' => 'High', 'like' => 'Moderate', 'rank' => 'High',
            'action_status' => 'Active Risk', 'risk_response' => 'Mitigate', 'action_target' => '2026-12-31', 'number_of_escalated_times' => 2,
            'last_escalated_time' => '2026-10-11', 'created_at' => '2026-01-15', 'postedby' => 'EMP1', 'review_date' => '2026-01-20', 'disapprovereason' => 'Please add evidence.']);
        foreach (['department' => (object) ['departmentname' => 'Maintenance'], 'riskreviewer' => (object) ['name' => 'Morgan Reed'],
            'postedByRisk' => (object) ['name' => 'Alice Walker'], 'approvedByRisk' => (object) ['name' => 'Morgan Reed'], 'division' => null, 'area' => null,
            'opportunities' => collect([(object) ['dep_id' => 'OPP12']]), 'mitigations' => collect([
                (object) ['id' => 7, 'action_name' => 'Qualify alternative suppliers', 'action_id' => 'OPE211/1/26', 'priority_level' => 'high', 'due_date' => '2026-12-01', 'progress' => 75, 'status' => 'In progress'],
                (object) ['id' => 8, 'action_name' => 'Verify contingency inventory', 'action_id' => 'OPE211/2/26', 'priority_level' => 'medium', 'due_date' => '2026-11-10', 'progress' => 100, 'status' => config('constants.risk_management.mitigation.closed')],
            ])] as $name => $relation) $risk->setRelation($name, $relation);
        $history = (new \App\RiskHistory)->forceFill(['impa' => 'High', 'impa_updated' => 'Moderate', 'like' => 'Moderate', 'like_updated' => 'Low', 'rank' => 3, 'rank_updated' => 2, 'action' => 'Updated', 'created_at' => '2026-10-15']);
        $history->setRelation('riskOwner', (new User)->forceFill(['name' => 'Morgan Reed']));
        $vars = ['risk' => $risk, 'posted_user_image' => null, 'riskOwnerData' => ['owner@example.test' => ['name' => 'Alice Walker', 'employeeid' => 'EMP1', 'profile_picture' => null]],
            'riskOwners' => collect(), 'riskHistories' => collect([$history]), 'risk_comments' => collect(), 'risk_comments_count' => 0, 'model' => null];
        $html = view('user.risks.detail', $vars)->render();
        foreach (['risk-overview', 'risk-assessment', 'risk-actions', 'risk-history', 'risk-discussion', 'comment_form', 'reply_form', 'editcomment_form'] as $id) {
            $this->assertMatchesRegularExpression('/id=[\'\"]'.preg_quote($id, '/').'[\'\"]/', $html);
        }
        $this->assertStringContainsString(route('risk.detail.pdf', 211), $html);
        $this->assertStringContainsString(route('mitigations.index', ['riskId' => 211]), $html);
        $this->assertStringContainsString(route('mitigations.detail', 7), $html);
        $this->assertStringContainsString('aria-valuenow="75"', $html);
        $this->assertSame(1, substr_count($html, 'rd-matrix-cell rd-tone-high is-current'));
        $this->assertStringContainsString('1 <small>of 2 completed', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $html);
        $this->assertStringContainsString('data-risk-review', $html);
        $this->assertStringNotContainsString('@else', $html);
        $this->assertStringContainsString('The marker shows the recorded assessment.', $html);
        if (getenv('RISK_DETAIL_PREVIEW_PATH')) file_put_contents(getenv('RISK_DETAIL_PREVIEW_PATH'), $html);
        $reportVars = array_merge($vars, ['clientDetails' => null]);
        $report = view('user.risks.detail_pdf', $reportVars)->render();
        $this->assertStringContainsString('Risk assessment report', $report);
        $this->assertStringContainsString('Qualify alternative suppliers', $report);
        $this->assertStringNotContainsString('@else', $report);
        $pdf = \PDF::loadView('user.risks.detail_pdf', $reportVars)->output();
        $this->assertStringStartsWith('%PDF-', $pdf);
        $this->assertStringContainsString('%%EOF', $pdf);
        if (getenv('RISK_DETAIL_PDF_PREVIEW_PATH')) file_put_contents(getenv('RISK_DETAIL_PDF_PREVIEW_PATH'), $pdf);
        $formVars = ['data' => collect([$risk]), 'hml' => collect([(object) ['name' => 'High'], (object) ['name' => 'Moderate'], (object) ['name' => 'Low']]),
            'mn' => collect([(new User)->forceFill(['id' => 1, 'name' => 'Alice Walker', 'email' => 'owner@example.test'])]), 'strategy' => collect([(object) ['name' => 'Mitigate']]), 'Rid' => 211, 'model' => null, 'errors' => new \Illuminate\Support\ViewErrorBag()];
        $form = view('user.risks.updaterisk', $formVars)->render();
        foreach (['Risk context', 'Risk details', 'Assess & assign', 'Initial risk review'] as $heading) $this->assertStringContainsString($heading, $form);
        foreach (['Riskid', 'Riskar', 'Riskgp', 'riskname', 'riskdec', 'riskdri', 'existing_controls', 'riskscr', 'risk_response', 'riskowner[]', 'impa', 'like', 'posted'] as $name) $this->assertStringContainsString('name="'.$name.'"', $form);
        $this->assertStringContainsString('name="impa" id="impact" value="High"', $form);
        $this->assertStringContainsString('name="like" id="likelihood" value="Moderate"', $form);
        $this->assertStringContainsString('>Approve</button>', $form);
        $legacyVars = array_merge($formVars, ['riskinfo' => collect(), 'riskreviewers' => collect(), 'rskgrp' => collect(), 'departments' => collect(), 'id' => 211]);
        foreach (['riskreview', 'resubmit', 'useredit', 'closure', 'reopenrisk', 'ownrespo'] as $page) {
            $legacy = view('user.risks.'.$page, $legacyVars)->render();
            $this->assertStringContainsString('rf-page-header', $legacy);
            $this->assertStringContainsString('rf-section-break', $legacy);
        }
        if (getenv('RISK_INITIAL_REVIEW_PREVIEW_PATH')) file_put_contents(getenv('RISK_INITIAL_REVIEW_PREVIEW_PATH'), $form);
        $risk->setAttribute('impa', null); $risk->setAttribute('like', null); $risk->setAttribute('rank', null); $risk->setRelation('mitigations', collect());
        $empty = view('user.risks.detail', $vars)->render();
        $this->assertStringContainsString('Unassessed', $empty);
        $this->assertStringContainsString('Select impact and likelihood', $empty);
        $this->assertStringNotContainsString('is-current', $empty);
        $this->assertStringContainsString('No mitigation actions yet', $empty);
    }
}
