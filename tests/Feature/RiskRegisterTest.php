<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RiskRegisterTest extends TestCase
{
    private function renderRegister($canManage = true, $empty = false, $stress = false)
    {
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        // Isolate the real register view from the unrelated global composer,
        // which needs the entire application database and customer fixtures.
        app('events')->forget('composing: *');
        $user = new User(['name' => 'Morgan Reed', 'email' => 'reviewer@example.test', 'employeeid' => 'TEST1']);
        $user->id = 1;
        $this->actingAs($user);
        foreach (['edit-risk', 'edit-all', 'delete-risk', 'delete-all', 'risk-change-riskowner'] as $permission) {
            Gate::define($permission, function ($user) use ($canManage) { return $canManage; });
        }
        $rows = collect();
        for ($i = 1; $i <= ($empty ? 0 : 31); $i++) {
            $index = ($i - 1) % 4;
            $rows->push((object) [
                'Rid' => $i, 'id' => $i, 'grp_id' => 'OPE'.(190 + $i),
                'riskname' => ['Critical spare-part shortages', 'Disruption to supplier agreements', 'Unauthorized access to business data', 'Escalating purchased-material costs'][$index].' — item '.$i,
                'riskgroup' => ['Operational', 'Strategic', 'Operational', 'Financial'][$index],
                'department' => (object) ['departmentname' => ['Maintenance', 'Finance', 'IT', 'Procurement'][$index]],
                'rank' => ['High', 'Moderate', 'Low', 'Moderate'][$index],
                'risk_owner' => $i === 1 ? 'reviewer@example.test' : ($index === 2 ? '' : 'owner@example.test'),
                'risk_response' => $index === 2 ? '' : 'Mitigate',
                'action_status' => ['Active Risk', 'Under review', 'Rejected', 'Awaiting Periodic Review'][$index],
                'postedby' => 'TEST1', 'riskreviewer_id' => 1, 'riskreviewer' => (object) ['email' => 'reviewer@example.test'],
            ]);
        }
        if ($stress && !$empty) {
            $rows[0]->riskname = 'Production interruption caused by prolonged unavailability of specialized critical spare parts from geographically concentrated suppliers during concurrent logistics and cross-border disruption';
            $rows[0]->riskgroup = 'Operational resilience and business continuity across critical production processes';
            $rows[0]->department->departmentname = 'Central engineering, maintenance and production support department';
            $rows[0]->risk_owner = 'reviewer@example.test,owner@example.test,second@example.test,third@example.test';
            $rows[0]->risk_response = 'Mitigate through supplier diversification, continuity planning and contingency inventory';
            $rows[1]->riskname = str_repeat('UnbrokenRiskIdentifier', 8);
        }
        return view('user.risks.risk', [
            'data' => $rows, 'email' => collect(['reviewer@example.test']),
            'riskOwnerData' => array_filter([
                'owner@example.test' => ['name' => 'Alice Walker', 'employeeid' => 'E02', 'profile_picture' => null],
                'reviewer@example.test' => ['name' => $stress ? 'Morgan Alexandra Catherine Reed-Montgomery, regional engineering and continuity coordinator' : 'Morgan Reed', 'employeeid' => 'TEST1', 'profile_picture' => null],
                'second@example.test' => ['name' => 'Alexandra Catherine Montgomery-Williams', 'employeeid' => 'E03', 'profile_picture' => null],
                'third@example.test' => ['name' => 'Christopher James Anderson', 'employeeid' => 'E04', 'profile_picture' => null],
            ], function ($owner, $email) use ($stress) { return $stress || !in_array($email, ['second@example.test', 'third@example.test']); }, ARRAY_FILTER_USE_BOTH),
        ])->render();
    }

    public function testRegisterHasProminentCreateAndExportOptions()
    {
        $html = $this->renderRegister();
        $this->assertStringContainsString('Add Risk', $html);
        $this->assertSame(1, substr_count($html, 'data-risk-theme-select'));
        $this->assertLessThan(strpos($html, '</head>'), strpos($html, 'js/app-theme.js'));
        $this->assertStringContainsString('risk-register-ai-toggle', $html);
        $this->assertStringContainsString('Review this register', $html);
        $this->assertStringContainsString(route('risk.addrisk'), $html);
        $this->assertStringContainsString('Search risk register', $html);
        foreach (['pdf', 'excel', 'word'] as $format) $this->assertStringContainsString(route('risk.exportData', $format), $html);
        $this->assertStringContainsString('id="edit"', $html);
        $this->assertStringContainsString('id="del"', $html);
        $this->assertStringContainsString('Select risks on this page', $html);
        $this->assertStringContainsString('Initial Review', $html);
        $this->assertStringContainsString('Periodic Review', $html);
        $this->assertStringContainsString('Add Mitigating Action', $html);
        $this->assertStringContainsString('Resubmit Risk', $html);
        $this->assertStringContainsString('Risk History', $html);
        if (getenv('RISK_REGISTER_PREVIEW_PATH')) file_put_contents(getenv('RISK_REGISTER_PREVIEW_PATH'), $html);
    }

    public function testLongTextAndMultipleOwnersRemainAvailableWithoutForcingTallRows()
    {
        $html = $this->renderRegister(true, false, true);
        $this->assertStringContainsString('prolonged unavailability of specialized critical spare parts', $html);
        $this->assertStringContainsString('register-name-text', $html);
        $this->assertStringContainsString('Show 3 additional owners', $html);
        $this->assertStringContainsString('Alexandra Catherine Montgomery-Williams', $html);
        $this->assertStringNotContainsString('id="risk-row-layout"', $html);
        $this->assertStringContainsString('data-density="compact"', $html);
        if (getenv('RISK_REGISTER_STRESS_PREVIEW_PATH')) file_put_contents(getenv('RISK_REGISTER_STRESS_PREVIEW_PATH'), $html);
    }

    public function testBulkActionsStayHiddenWithoutExistingPermissions()
    {
        $html = $this->renderRegister(false);
        $this->assertStringNotContainsString('id="edit"', $html);
        $this->assertStringNotContainsString('id="del"', $html);
        $this->assertStringNotContainsString('Change Risk Owner/area', $html);
        $this->assertStringContainsString('Risk History', $html);
    }

    public function testDataTableColumnContractAndEmptyRegisterRenderCorrectly()
    {
        foreach ([false, true] as $empty) {
            $html = $this->renderRegister(true, $empty);
            $old = libxml_use_internal_errors(true);
            $dom = new \DOMDocument;
            $dom->loadHTML($html);
            libxml_clear_errors(); libxml_use_internal_errors($old);
            $xpath = new \DOMXPath($dom);
            $this->assertSame(13, $xpath->query('//table[@id="tabledata"]/thead/tr/th')->length);
            foreach ($xpath->query('//table[@id="tabledata"]/tbody/tr') as $row) $this->assertSame(13, $row->getElementsByTagName('td')->length);
            $this->assertSame($empty ? 0 : 31, $xpath->query('//table[@id="tabledata"]/tbody/tr')->length);
            $this->assertStringContainsString('Clear search &amp; filters', $html);
        }
    }
}
