<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RiskRegisterTest extends TestCase
{
    private function renderRegister($canManage = true, $empty = false)
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
        return view('user.risks.risk', [
            'data' => $rows, 'email' => collect(['reviewer@example.test']),
            'riskOwnerData' => [
                'owner@example.test' => ['name' => 'Alice Walker', 'employeeid' => 'E02', 'profile_picture' => null],
                'reviewer@example.test' => ['name' => 'Morgan Reed', 'employeeid' => 'TEST1', 'profile_picture' => null],
            ],
        ])->render();
    }

    public function testRegisterHasProminentCreateAndExportOptions()
    {
        $html = $this->renderRegister();
        $this->assertStringContainsString('Add Risk', $html);
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
