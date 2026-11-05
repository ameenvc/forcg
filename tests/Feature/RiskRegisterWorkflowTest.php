<?php
namespace Tests\Feature;

use Tests\TestCase;

class RiskRegisterWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
    }

    public function testCriteriaAndMitigationHaveConnectedNavigationAndPreserveWorkflowControls()
    {
        $criteria = view('user.risks.racm.index', ['riskAcms' => collect(), 'users' => collect(), 'allDeptNames' => '', 'model' => null, 'errors' => new \Illuminate\Support\ViewErrorBag()])->render();
        $this->assertStringContainsString('Add criteria', $criteria);
        $this->assertStringContainsString('id="request-review-approve"', $criteria);
        $this->assertStringContainsString('id="save-request"', $criteria);
        $this->assertStringContainsString('window.location.reload();', $criteria);
        $this->assertStringNotContainsString('window.table', $criteria);
        $this->assertStringNotContainsString('workflow-guide', $criteria);
        $this->assertStringContainsString("departmentNames, 'dept_dropdown', 2", $criteria);
        $this->assertStringContainsString('risk-workflow.css', $criteria);
        $vars = ['mitigations' => collect(), 'riskId' => null, 'allOwnerNames' => '', 'allDeptNames' => '', 'allPriorityLevel' => '', 'allYears' => '', 'allStatus' => '', 'allResourceRequired' => '', 'model' => null, 'currentUserId' => null, 'reviewerIds' => []];
        $actions = view('user.risks.mitigation.index', $vars)->render();
        $this->assertStringContainsString('Choose risk to add action', $actions);
        $this->assertStringContainsString('Action tracker', $actions);
        $this->assertStringNotContainsString('height:400px', $actions);
        $this->assertStringNotContainsString('dataTables.responsive.js', $actions);
        $this->assertSame(1, substr_count($actions, '<!doctype html>'));
        $this->assertStringContainsString("table.columns(columnIndex).search('').draw()", $actions);
        foreach ([$criteria, $actions] as $html) {
            $this->assertStringContainsString(route('racm.index'), $html);
            $this->assertStringContainsString(route('mitigations.index'), $html);
            $this->assertStringNotContainsString('workflow-guide', $html);
            $this->assertStringContainsString('js/risk-workflow.js', $html);
            $this->assertStringContainsString('css/risk-tables.css', $html);
            $this->assertStringNotContainsString('>Organization AI</a>', $html);
        }
        $scoped = view('user.risks.mitigation.index', array_merge($vars, ['riskId' => 42]))->render();
        $this->assertStringContainsString(route('risk.detail', 42), $scoped);
        $this->assertStringContainsString('Show all actions', $scoped);
        if (getenv('RISK_WORKFLOW_PREVIEW_DIR')) {
            file_put_contents(getenv('RISK_WORKFLOW_PREVIEW_DIR').'/risk-criteria-preview.html', $criteria);
            file_put_contents(getenv('RISK_WORKFLOW_PREVIEW_DIR').'/risk-mitigation-preview.html', $scoped);
        }
    }
}
