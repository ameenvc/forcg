<?php
namespace Tests\Feature;
use App\Services\DepartmentPerformanceService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Tests\TestCase;
class DepartmentPerformanceTest extends TestCase
{
    protected function setUp():void{
        parent::setUp();Carbon::setTestNow(Carbon::parse('2026-11-07 12:00:00'));
        Schema::create('departments',function(Blueprint $t){$t->increments('id');$t->string('departmentname');$t->boolean('is_active')->default(1);});
        DB::table('departments')->insert([['id'=>1,'departmentname'=>'Production','is_active'=>1],['id'=>2,'departmentname'=>'Maintenance','is_active'=>1],['id'=>3,'departmentname'=>'Finance','is_active'=>1],['id'=>4,'departmentname'=>'Former department','is_active'=>0]]);
        Schema::create('mitigations',function(Blueprint $t){$t->increments('id');$t->string('department_id')->nullable();$t->string('status')->nullable();$t->string('due_date')->nullable();$t->string('reviewed_date')->nullable();$t->string('priority_level')->nullable();$t->string('action_name')->nullable();$t->softDeletes();});
    }
    protected function tearDown():void{Carbon::setTestNow();parent::tearDown();}
    private function action($department=1,$extra=[]){DB::table('mitigations')->insert(array_merge(['department_id'=>(string)$department,'status'=>'Closed','due_date'=>'2026-11-02','reviewed_date'=>'2026-11-01','priority_level'=>'Moderate','action_name'=>'Private action title'], $extra));}
    private function data($modules=['riskmanagement']){return(new DepartmentPerformanceService)->snapshot($modules);}
    private function rows($data){return array_column($data['departments'],null,'id');}
    private function comparable(){foreach(range(1,10) as $i){$this->action(1);$this->action(2,$i<=8?[]:['status'=>'Open','due_date'=>'2026-09-08','reviewed_date'=>null,'priority_level'=>'High']);}}
    public function testRanksUseActualDueDatesCompletionAndAgeSeverityWithEvidenceDenominators(){
        $this->comparable();$data=$this->data();$rows=$this->rows($data);
        $this->assertSame(100.0,$rows[1]['score']);$this->assertSame(54.3,$rows[2]['score']);
        $this->assertSame(80.0,$rows[2]['metrics']['on_time']['score']);$this->assertSame(18.2,$rows[2]['metrics']['backlog']['score']);
        $this->assertSame(1,$rows[1]['rank']);$this->assertSame(2,$rows[2]['rank']);$this->assertSame(60,$rows[1]['coverage']);$this->assertSame(10,$rows[1]['records']);
        $this->assertSame(2,$rows[2]['overdue']);$this->assertNull($rows[1]['change']);$this->assertStringNotContainsString('Private action',json_encode($data));
    }
    public function testLowEvidenceAndUnmappedDepartmentsNeverReceiveRankOrPerfectScore(){
        foreach(range(1,4) as $i)$this->action();$this->action('[1,2]');$this->action(11);$this->action(4);
        $data=$this->data();$rows=$this->rows($data);$this->assertNull($rows[1]['score']);$this->assertNull($rows[1]['rank']);$this->assertNull($rows[3]['score']);
        $this->assertCount(3,$rows);$this->assertSame(3,$data['unassigned']);$this->assertSame('Insufficient data',$rows[1]['status']);
    }
    public function testFutureDeletedCancelledAndOldCompletedRecordsEarnNoCredit(){
        foreach([['status'=>'Cancelled'],['deleted_at'=>'2026-11-01'],['due_date'=>'2026-12-01'],['due_date'=>'2025-01-01']] as $extra)$this->action(1,$extra);
        $data=$this->rows($this->data());$this->assertSame(0,$data[1]['records']);$this->assertNull($data[1]['score']);
        $this->action(1,['status'=>'Open','due_date'=>'2025-01-01','reviewed_date'=>null]);$data=$this->rows($this->data());$this->assertSame(1,$data[1]['overdue']);$this->assertSame(1,$data[1]['records']);
    }
    public function testMissingCompletionEvidenceAndInvalidDatesPreventMisleadingRank(){
        foreach(range(1,5) as $i)$this->action(1,['reviewed_date'=>null]);
        $data=$this->rows($this->data());$this->assertNull($data[1]['metrics']['on_time']['score']);$this->assertSame(25,$data[1]['coverage']);$this->assertNull($data[1]['score']);
        foreach(range(1,5) as $i)$this->action(2);$this->action(2,['due_date'=>'31/02/2026']);$this->action(2,['due_date'=>null]);
        $data=$this->rows($this->data());$this->assertSame(71.4,$data[2]['completeness']);$this->assertNull($data[2]['score']);
    }
    public function testEqualScoresShareRankAndSingleComparableDepartmentIsNotFirstPlace(){
        $this->comparable();foreach(range(1,10) as $i)$this->action(3);$rows=$this->rows($this->data());
        $this->assertSame(1,$rows[1]['rank']);$this->assertSame(1,$rows[3]['rank']);$this->assertSame(3,$rows[2]['rank']);
        DB::table('departments')->whereIn('id',[2,3])->update(['is_active'=>0]);$rows=$this->rows($this->data());$this->assertNull($rows[1]['rank']);$this->assertSame('No comparable peer',$rows[1]['status']);
    }
    public function testAuditsObjectivesAndDifferentCoverageProduceSeparateComparisonGroups(){
        $this->comparable();
        Schema::create('audit',function(Blueprint $t){$t->increments('id');$t->integer('department');$t->string('status');$t->string('enddate');});
        Schema::create('objectives',function(Blueprint $t){$t->increments('Oid');$t->integer('department');$t->string('status');$t->string('todate');});
        foreach(['Completed','Planned'] as $state)DB::table('audit')->insert(['department'=>1,'status'=>$state,'enddate'=>'2026-10-01']);
        foreach(['Achieved','Not Achieved','Not Achieved','Not Achieved'] as $state)DB::table('objectives')->insert(['department'=>1,'status'=>$state,'todate'=>'2026-10-01']);
        $data=$this->data(['riskmanagement','auditmanagement','objectives']);$rows=$this->rows($data);
        $this->assertSame(50.0,$rows[1]['metrics']['audits']['score']);$this->assertSame(25.0,$rows[1]['metrics']['objectives']['score']);$this->assertSame(75.0,$rows[1]['score']);
        $this->assertSame(100,$rows[1]['coverage']);$this->assertCount(2,$data['cohorts']);$this->assertNull($rows[1]['rank']);$this->assertNull($rows[2]['rank']);
    }
    public function testDisabledModulesRunNoQueriesAndDoNotCountTheirSources(){
        DB::enableQueryLog();DB::flushQueryLog();$data=$this->data([]);$queries=DB::getQueryLog();DB::disableQueryLog();
        $this->assertFalse($data['available']);$this->assertSame([],$queries);
        foreach(range(1,6) as $i)$this->action();$data=$this->rows($this->data(['hr']));$this->assertNull($data[1]['score']);
    }
    public function testQueryCountDoesNotGrowWithDepartmentOrRecordCount(){
        $this->action();DB::enableQueryLog();DB::flushQueryLog();$this->data();$small=count(DB::getQueryLog());
        foreach(range(10,40) as $i){DB::table('departments')->insert(['id'=>$i,'departmentname'=>'Department '.$i,'is_active'=>1]);$this->action($i);}
        DB::flushQueryLog();$this->data();$large=count(DB::getQueryLog());DB::disableQueryLog();$this->assertSame($small,$large);
    }
    public function testDashboardIncludesDepartmentComparisonAndFullBreakdownTab()
    {
        $this->comparable();$this->action(3);$this->action(3);
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));app('events')->forget('composing: *');
        $dashboard=(new \App\Services\OrganizationDashboardService)->snapshot(['riskmanagement']);
        $html=view('user.userdashboard',['dashboard'=>$dashboard,'company'=>'Sample company','model'=>null])->render();
        $this->assertStringContainsString('data-dashboard-tab="od-departments"',$html);
        $this->assertStringContainsString('Department ranking &amp; score breakdown',$html);
        $this->assertStringContainsString('Insufficient data',$html);$this->assertStringContainsString('#1 / 2',$html);
        if(getenv('DEPARTMENT_DASHBOARD_PREVIEW_PATH'))file_put_contents(getenv('DEPARTMENT_DASHBOARD_PREVIEW_PATH'),$html);
    }
    public function testBreakdownRendersEscapedNamesAndExplainsProvisionalRanks(){
        $this->comparable();DB::table('departments')->where('id',1)->update(['departmentname'=>'<script>bad()</script>']);
        $html=view('user.department-performance',['performance'=>$this->data(),'compact'=>false])->render();
        $this->assertStringContainsString('&lt;script&gt;bad()&lt;/script&gt;',$html);$this->assertStringNotContainsString('<script>bad()</script>',$html);
        $this->assertStringContainsString('Insufficient data',$html);$this->assertStringContainsString('Group rank',$html);$this->assertStringContainsString('On-time action completion',$html);
    }
}
