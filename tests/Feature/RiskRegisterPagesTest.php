<?php

namespace Tests\Feature;

use App\Http\Controllers\RiskController;
use App\Services\RiskAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RiskRegisterPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
    }

    public function testClosedRegisterPreservesActionsExportsOwnersAndEmptyState()
    {
        $rows = collect();
        for ($i = 1; $i <= 31; $i++) {
            $rows->push((object) ['Rid' => $i, 'grp_id' => 'OPE'.$i, 'riskname' => $i === 1 ? 'Production interruption from critical supplier concentration and concurrent logistics disruption across multiple regions' : 'Closed risk '.$i,
                'riskgroup' => $i % 2 ? 'Operational' : 'Strategic', 'department' => (object) ['departmentname' => $i % 2 ? 'Maintenance' : 'Finance'],
                'rank' => $i % 3 ? 'High' : 'Low', 'risk_owner' => 'owner@example.test,second@example.test', 'risk_response' => 'Mitigate', 'action_target' => '2026-12-31', 'actionst' => 'Complete']);
        }
        $users = collect([(object) ['email' => 'owner@example.test', 'name' => 'Alice Walker', 'employeeid' => 'E01'], (object) ['email' => 'second@example.test', 'name' => 'Morgan Reed', 'employeeid' => 'E02']]);
        foreach ([$rows, collect()] as $data) {
            $html = view('user.risks.closedrisks', ['data2' => $data, 'users' => $users])->render();
            $this->assertStringContainsString('Closed risks', $html);
            $this->assertStringContainsString('data-closed-export', $html);
            $this->assertStringContainsString('aria-current="page"', $html);
            $this->assertStringNotContainsString('overflow-y: hidden', $html);
            if ($data->count()) {
                $this->assertStringContainsString('Alice Walker · E01, Morgan Reed · E02', $html);
                $this->assertStringContainsString('/user/risks/reopenrisk?val=1', $html);
                $this->assertStringContainsString('data-closed-view=', $html);
                $this->assertStringContainsString('data-density="compact"', $html);
                if (getenv('RISK_CLOSED_PREVIEW_PATH')) file_put_contents(getenv('RISK_CLOSED_PREVIEW_PATH'), $html);
            } elseif (getenv('RISK_CLOSED_EMPTY_PREVIEW_PATH')) file_put_contents(getenv('RISK_CLOSED_EMPTY_PREVIEW_PATH'), $html);
        }
    }

    public function testAnalysisCountsAgreeAcrossGroupsAndDepartmentsAndExcludeDeletedRisks()
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->increments('Rid'); $table->string('riskgroup')->nullable(); $table->string('rank')->nullable(); $table->integer('riskarea')->nullable(); $table->string('action_status')->nullable(); $table->string('date')->nullable(); $table->dateTime('created_at')->nullable(); $table->string('risk_owner')->nullable(); $table->string('risk_response')->nullable(); $table->softDeletes();
        });
        Schema::create('departments', function (Blueprint $table) { $table->increments('id'); $table->string('code')->nullable(); });
        Schema::create('modenadisable', function (Blueprint $table) { $table->increments('id'); });
        DB::table('departments')->insert([['id' => 1, 'code' => 'ENG'], ['id' => 2, 'code' => 'FIN']]);
        DB::table('risks')->insert([
            ['riskgroup' => 'Strategic', 'rank' => 'High', 'riskarea' => 1, 'action_status' => 'Active Risk', 'deleted_at' => null],
            ['riskgroup' => 'Operational', 'rank' => 'Moderate', 'riskarea' => 1, 'action_status' => 'Closed', 'deleted_at' => null],
            ['riskgroup' => 'Operational', 'rank' => 'Low', 'riskarea' => 999, 'action_status' => 'Active Risk', 'deleted_at' => null],
            ['riskgroup' => null, 'rank' => null, 'riskarea' => null, 'action_status' => 'Under review', 'deleted_at' => null],
            ['riskgroup' => 'Strategic', 'rank' => 'High', 'riskarea' => 2, 'action_status' => 'Active Risk', 'deleted_at' => '2026-01-01'],
        ]);
        DB::table('risks')->where('Rid', 1)->update(['created_at' => '2026-01-10 00:00:00', 'date' => '10-02-2026 02:30:00 PM', 'risk_owner' => 'owner@example.test', 'risk_response' => 'Mitigate']);
        DB::table('risks')->where('Rid', 2)->update(['created_at' => '2026-02-10 23:59:59', 'risk_response' => 'Accept']);
        DB::table('risks')->where('Rid', 3)->update(['date' => '11-02-2026 02:30:00 PM']);
        DB::table('risks')->where('Rid', 4)->update(['date' => '31-02-2026']);
        $view = (new RiskController)->analysis(Request::create('/user/risks/analysis'), app(RiskAnalysisService::class));
        $data = $view->getData()['analysisData'];
        $this->assertSame(['total' => 4, 'High' => 1, 'Moderate' => 1, 'Low' => 1, 'Unassessed' => 1], $data['totals']);
        $this->assertSame(2, $data['departments']['ENG']['total']);
        $this->assertSame(2, $data['departments']['Unassigned']['total']);
        $this->assertSame(1, $data['groups']['Operational']['counts']['Moderate']);
        $this->assertArrayNotHasKey('FIN', $data['departments']);
        $this->assertSame($data['totals']['total'], array_sum(array_column($data['departments'], 'total')));
        $html = $view->render();
        $this->assertStringContainsString('including closed records', $html);
        $this->assertStringContainsString('Department comparison', $html);
        $this->assertStringNotContainsString('everything looks great', $html);
        if (getenv('RISK_ANALYSIS_PREVIEW_PATH')) file_put_contents(getenv('RISK_ANALYSIS_PREVIEW_PATH'), $html);
        $this->assertSame(['unassigned_owner' => 3, 'missing_response' => 2, 'undated' => 1], $data['quality']);
        $this->assertSame(['2026-01-10' => 1, '2026-02-10' => 1, '2026-02-11' => 1], $data['dates']);
        $request = Request::create('/user/risks/analysis', 'GET', ['from' => '2026-02-10', 'to' => '2026-02-11'], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = (new RiskController)->analysis($request, app(RiskAnalysisService::class));
        $this->assertSame(4, $response->getData(true)['analysisData']['totals']['total']);
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        foreach (['analysis-date-form', 'analysis-refresh', 'analysis-live', 'Risks added over time'] as $removed) $this->assertStringNotContainsString($removed, $html);
        DB::table('risks')->delete();
        $empty = (new RiskController)->analysis(Request::create('/user/risks/analysis'), app(RiskAnalysisService::class));
        $this->assertSame(0, $empty->getData()['analysisData']['totals']['total']);
        $this->assertStringContainsString('No risk groups to display yet.', $empty->render());
        if (getenv('RISK_ANALYSIS_EMPTY_PREVIEW_PATH')) file_put_contents(getenv('RISK_ANALYSIS_EMPTY_PREVIEW_PATH'), $empty->render());
    }
}
