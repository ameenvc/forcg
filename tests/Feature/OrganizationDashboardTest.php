<?php
namespace Tests\Feature;
use App\Services\OrganizationDashboardService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Tests\TestCase;
class OrganizationDashboardTest extends TestCase
{
    protected function setUp():void{parent::setUp();Carbon::setTestNow(Carbon::parse('2026-11-06 12:00:00'));}
    protected function tearDown():void{Carbon::setTestNow();parent::tearDown();}
    private function table(){Schema::create('mitigations',function(Blueprint $t){$t->increments('id');$t->string('status')->nullable();$t->string('due_date')->nullable();$t->string('action_name')->nullable();$t->softDeletes();});}
    private function row($extra=[]){DB::table('mitigations')->insert(array_merge(['status'=>'Open','due_date'=>'2026-11-05','action_name'=>'Private task title'],$extra));}
    private function snapshot($enabled=['riskmanagement']){return (new OrganizationDashboardService)->snapshot($enabled);}
    public function testDisabledModulesNeverAppearOrLoadTheirRecords()
    {
        $this->table();$this->row();DB::enableQueryLog();DB::flushQueryLog();$d=$this->snapshot([]);$queries=DB::getQueryLog();DB::disableQueryLog();
        $this->assertSame([],$d['modules']);$this->assertSame(0,$d['totals']['records']);
        $this->assertSame([],$queries);
    }
    public function testAggregateDeadlinesExcludeDeletedCancelledClosedAndRejectAmbiguousInvalidDates()
    {
        $this->table();$this->row();$this->row(['status'=>'Closed']);$this->row(['status'=>'Cancelled']);$this->row(['deleted_at'=>'2026-11-01']);
        $this->row(['status'=>'Review Pending','due_date'=>'2026-11-06']);$this->row(['due_date'=>'31/02/2026']);$this->row(['status'=>null]);$this->row(['due_date'=>'2026-11-13']);
        $d=$this->snapshot();$this->assertSame(6,$d['totals']['records']);$this->assertSame(4,$d['totals']['open']);$this->assertSame(1,$d['totals']['closed']);
        $this->assertSame(1,$d['totals']['overdue']);$this->assertSame(2,$d['totals']['due_soon']);$this->assertSame(1,$d['totals']['unknown']);$this->assertSame(1,$d['totals']['decisions']);$this->assertSame(2,$d['upcoming'][0]['count']);$this->assertSame('Mitigation actions',$d['upcoming'][0]['name']);
        $this->assertStringNotContainsString('Private task title',json_encode($d));
        $this->assertSame('Partial coverage',$d['modules'][0]['state']);
    }
    public function testUnavailableFeedsAreNotPresentedAsCompleteZeroWork()
    {
        $d=$this->snapshot(['hr','occhealth']);$this->assertSame('Data unavailable',$d['modules'][0]['state']);
        $this->assertSame('Directory only',$d['modules'][1]['state']);$this->assertSame(0,$d['connected']);
    }
    public function testDuplicateWorkflowViewsOfSameTableAreCountedOnce()
    {
        Schema::create('incident_reports',function(Blueprint $t){$t->increments('id');$t->string('investigation_status');$t->string('reviewed');});
        DB::table('incident_reports')->insert(['investigation_status'=>'Under Investigation','reviewed'=>'Under review']);
        $d=$this->snapshot(['incidentreport']);$this->assertSame(1,$d['totals']['records']);$this->assertCount(1,$d['modules'][0]['feeds']);
    }
    public function testAllEnabledModuleShortcutsRemainVisibleWithoutOptionalSchema()
    {
        $registry=require config_path('my_board.php');$d=$this->snapshot(array_keys($registry['modules']));$this->assertCount(24,$d['modules']);
        $this->assertContains('Occupational health',array_column($d['modules'],'name'));
        $this->assertContains('Customer satisfaction',array_column($d['modules'],'name'));
    }
    public function testComplianceExceptionsExcludeSoftDeletedRecords()
    {
        Schema::create('compliance',function(Blueprint $t){$t->increments('id');$t->string('pro_status');$t->string('comp_status');$t->softDeletes();});
        DB::table('compliance')->insert([['pro_status'=>'Approved','comp_status'=>'Not Complied','deleted_at'=>null],['pro_status'=>'Approved','comp_status'=>'Not Complied','deleted_at'=>'2026-01-01']]);
        $this->assertSame(1,$this->snapshot(['compliance'])['exceptions']);$this->assertSame(0,$this->snapshot([])['exceptions']);
    }
    public function testQueryCountDoesNotGrowWithNumberOfRecords()
    {
        $this->table();$this->row();DB::enableQueryLog();DB::flushQueryLog();$this->snapshot();$small=count(DB::getQueryLog());
        foreach(range(1,100) as $i)$this->row();DB::flushQueryLog();$d=$this->snapshot();$large=count(DB::getQueryLog());DB::disableQueryLog();
        $this->assertSame($small,$large);$this->assertSame(101,$d['totals']['records']);
    }
    public function testExecutiveRiskExposureExcludesClosedDeletedAndCancelledRecords()
    {
        Schema::create('risks',function(Blueprint $t){$t->increments('Rid');$t->string('action_status');$t->string('rank')->nullable();$t->softDeletes();});
        foreach([['Active Risk','High',null],['Closed','High',null],['Cancelled','High',null],['Active Risk','High','2026-01-01'],['Under review','Moderate',null],['Active Risk',null,null]] as $values)DB::table('risks')->insert(array_combine(['action_status','rank','deleted_at'],$values));
        $d=$this->snapshot();$this->assertTrue($d['risk']['available']);$this->assertSame(1,$d['risk']['High']);$this->assertSame(1,$d['risk']['Moderate']);$this->assertSame(1,$d['risk']['Unassessed']);
        $this->assertFalse($this->snapshot([])['risk']['available']);
    }
    public function testExecutiveSummariesKeepAreasClosureAndComplianceDenominatorsVisible()
    {
        $this->table();$this->row();$this->row(['status'=>'Closed']);
        Schema::create('compliance',function(Blueprint $t){$t->increments('id');$t->string('pro_status');$t->string('comp_status')->nullable();});
        foreach([['Approved','Complied'],['Approved','Not Complied'],['Approved',null],['Cancelled','Not Complied']] as $values)DB::table('compliance')->insert(array_combine(['pro_status','comp_status'],$values));
        $d=$this->snapshot(['riskmanagement','compliance']);
        $this->assertSame(1,$d['compliance']['Complied']);$this->assertSame(1,$d['compliance']['Not Complied']);$this->assertSame(1,$d['compliance']['Other']);$this->assertSame(1,$d['exceptions']);
        $this->assertSame(1,$d['closure'][0]['closed']);$this->assertSame(2,$d['closure'][0]['total']);$this->assertSame(2,$d['areas'][0]['modules']);
        $this->assertFalse($d['evidence']['available']);
    }
    public function testPendingDecisionsUseDefinedStagesAndCompletionProgressRatherThanWordMatches()
    {
        $this->table();Schema::table('mitigations',function(Blueprint $t){$t->integer('progress')->default(0);});
        $this->row(['status'=>'Action Reviewed, OK','progress'=>0]);
        $this->row(['status'=>'Review completed','progress'=>0]);
        $this->row(['status'=>'Awaiting Approval','progress'=>0]);
        $this->row(['status'=>'Open','progress'=>100]);
        $this->row(['status'=>'Closed','progress'=>100]);
        $d=$this->snapshot();$this->assertSame(2,$d['totals']['decisions']);
    }
    public function testDashboardRouteIsAuthenticated()
    {
        $this->withoutExceptionHandling();$this->expectException(\Illuminate\Auth\AuthenticationException::class);$this->getJson('/user/dashboard');
    }
    public function testDashboardControllerReturnsPrivateCompanySpecificOverview()
    {
        $this->withoutMiddleware();view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));app('events')->forget('composing: *');
        Schema::create('users',function(Blueprint $t){$t->increments('id');$t->string('name');$t->string('email');$t->string('type');$t->softDeletes();});
        DB::table('users')->insert(['id'=>1,'name'=>'Alex','email'=>'alex@example.test','type'=>'user']);$this->actingAs(\App\User::find(1));
        Schema::create('modenadisable',function(Blueprint $t){$t->increments('id');$t->integer('riskmanagement');$t->integer('hr');});
        DB::table('modenadisable')->insert(['riskmanagement'=>1,'hr'=>0]);$this->table();$this->row();
        $response=$this->get('/user/dashboard');$response->assertOk()->assertSee('Enterprise risk')->assertDontSee('Human resources');
        $this->assertStringContainsString('no-store',$response->headers->get('Cache-Control'));
    }
    public function testPageRendersCompanyAndEnabledModulesSafely()
    {
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));app('events')->forget('composing: *');$this->table();$this->row();$this->row(['due_date'=>'2026-11-10']);
        Schema::create('risks',function(Blueprint $t){$t->increments('Rid');$t->string('action_status');$t->string('rank');});
        DB::table('risks')->insert([['action_status'=>'Active Risk','rank'=>'High'],['action_status'=>'Active Risk','rank'=>'Moderate'],['action_status'=>'Active Risk','rank'=>'Low']]);
        Schema::create('compliance',function(Blueprint $t){$t->increments('id');$t->string('pro_status');$t->string('comp_status');});
        foreach(['Complied','Not Complied','Partially Complied'] as $state)DB::table('compliance')->insert(['pro_status'=>'Approved','comp_status'=>$state]);
        $dashboard=$this->snapshot(['riskmanagement','auditmanagement','compliance','hr','occhealth','customer_satisfaction']);
        $html=view('user.userdashboard',['dashboard'=>$dashboard,'company'=>'<script>bad()</script>','model'=>null])->render();
        $this->assertStringContainsString('&lt;script&gt;bad()&lt;/script&gt;',$html);$this->assertStringNotContainsString('<script>bad()</script>',$html);
        $this->assertStringContainsString('Enterprise risk',$html);$this->assertStringNotContainsString('Waste management',$html);$this->assertStringNotContainsString('Private task title',$html);
        $this->assertStringContainsString('1 due',$html);$this->assertStringContainsString('Open register',$html);$this->assertStringContainsString('Management overview',$html);$this->assertStringContainsString('data-dashboard-tab="od-modules"',$html);
        if(getenv('DASHBOARD_PREVIEW_PATH'))file_put_contents(getenv('DASHBOARD_PREVIEW_PATH'),$html);
    }
}
