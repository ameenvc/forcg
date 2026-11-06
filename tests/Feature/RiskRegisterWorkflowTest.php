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
        $this->assertStringContainsString('Add mitigation action', $actions);
        $this->assertStringContainsString(route('mitigations.create'), $actions);
        $this->assertStringNotContainsString('Action tracker', $actions);
        $this->assertStringNotContainsString('Criteria library', $criteria);
        preg_match('/<nav class="register-navigation".*?<\/nav>/s', $criteria, $navigation);
        $this->assertGreaterThan(strpos($navigation[0], '>Closed risks</a>'), strpos($navigation[0], '>Assessment criteria</a>'));
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
    public function testMitigationProgressHasAccurateAccessibleValuesAndSearchLabels()
    {
        foreach ([0, 25, 50, 75, 100, -10, 150] as $value) {
            $progress = max(0, min(100, $value));
            $html = view('user.risks.mitigation._status', ['mitigation' => (object) ['progress' => $value, 'status' => 'Active']])->render();
            $this->assertStringContainsString('aria-valuenow="'.$progress.'"', $html);
            $this->assertStringContainsString('width:'.$progress.'%', $html);
            $this->assertStringContainsString('<strong>'.$progress.'%</strong>', $html);
            $this->assertStringContainsString($progress === 0 ? 'Not started - 0%' : ($progress === 100 ? 'Completed - 100%' : 'In progress - 25%|50%|75%'), $html);
        }
        $closed = view('user.risks.mitigation._status', ['mitigation' => (object) ['progress' => 100, 'status' => config('constants.risk_management.mitigation.closed')]])->render();
        $this->assertStringContainsString('Mitigation progress: Closed', $closed);
        $this->assertStringContainsString('aria-valuenow="100"', $closed);
    }

    public function testModernCreateFormsPreserveExistingFieldsAndActions()
    {
        $criteria = view('user.risks.racm._form', ['departments' => collect(), 'users' => collect()])->render();
        $this->assertStringContainsString('id="save_racm"', $criteria);
        foreach (['risk_factor_title', 'process_id', 'risk_factor_description', 'high', 'moderate', 'low', 'unit'] as $name) {
            $this->assertStringContainsString('name="'.$name.'"', $criteria);
        }
        $action = view('user.risks.mitigation._form', ['riskId' => 42, 'actionOwners' => [], 'departments' => collect()])->render();
        foreach (['risk_id', 'owner', 'department_id', 'action_name', 'action_description', 'priority_level', 'due_date', 'attachment'] as $name) {
            $this->assertStringContainsString('name="'.$name.'"', $action);
        }
        $this->assertStringContainsString('risk-action-grid', $action);
        $this->assertStringNotContainsString('margin-left:79.5%', $action);
        foreach ([$criteria, $action] as $html) $this->assertStringContainsString('css/risk-forms.css', $html);
        if (getenv('RISK_WORKFLOW_PREVIEW_DIR')) {
            foreach (['criteria-form' => $criteria, 'action-form' => $action] as $name => $form) {
                file_put_contents(getenv('RISK_WORKFLOW_PREVIEW_DIR').'/risk-'.$name.'-preview.html', view('risk-form-workflow-preview', compact('form'))->render());
            }
        }
    }

    public function testDirectActionFormRequiresPrimaryRiskAndPreservesExistingRiskBoundForm()
    {
        $vars = ['riskId' => 'new', 'selectRisk' => true, 'selectedRiskId' => 42,
            'data' => collect([(object)['Rid' => 42, 'grp_id' => 'OPE42', 'riskname' => 'Supplier disruption']]),
            'deletedRisks' => collect(), 'actionOwners' => [], 'departments' => collect()];
        $html = view('user.risks.mitigation._form', $vars)->render();
        $this->assertStringContainsString('id="mitigation-primary-risk" name="risk_id" required', $html);
        $this->assertStringContainsString('value="42" selected', $html);
        $this->assertStringContainsString('OPE42 — Supplier disruption', $html);
        $this->assertStringNotContainsString('type="hidden" name="risk_id"', $html);
        $this->assertStringContainsString('Create action', $html);
        if (getenv('RISK_WORKFLOW_PREVIEW_DIR')) {
            file_put_contents(getenv('RISK_WORKFLOW_PREVIEW_DIR').'/risk-direct-action-preview.html', view('risk-form-workflow-preview', ['form' => $html])->render());
        }
        $this->assertStringContainsString('data-dismiss="modal">Cancel', $html);
        $this->assertSame('App\\Http\\Controllers\\MitigationController@create', app('router')->getRoutes()->match(\Illuminate\Http\Request::create('/user/riskmgmt/mitigations/create'))->getActionName());
        $bound = view('user.risks.mitigation._form', ['riskId' => 42, 'actionOwners' => [], 'departments' => collect()])->render();
        $this->assertStringContainsString('type="hidden" name="risk_id" value="42"', $bound);
        $this->assertStringNotContainsString('id="mitigation-primary-risk"', $bound);
    }

}
