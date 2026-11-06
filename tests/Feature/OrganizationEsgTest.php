<?php
namespace Tests\Feature;
use App\Services\OrganizationEsgService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
class OrganizationEsgTest extends TestCase
{
    private function snapshot($modules=[]) { return (new OrganizationEsgService)->snapshot($modules); }
    private function table($name,$field) { Schema::create($name,function(Blueprint $t)use($field){$t->increments('id');$t->string($field)->nullable();$t->softDeletes();}); }
    public function testMissingEvidenceNeverEarnsPerfectScore()
    {
        $s=$this->snapshot(['auditmanagement']);
        $this->assertNull($s['score']); $this->assertSame(0.0,$s['coverage']);
        $this->assertSame('Source unavailable',$s['indicators'][10]['state']);
        $this->assertSame('Evidence required',$s['indicators'][2]['state']);
    }
    public function testFixedWeightsKeepMissingEvidenceInDenominatorAndExcludeCancelledDeletedRecords()
    {
        $this->table('audit','status');
        DB::table('audit')->insert([['status'=>'Completed','deleted_at'=>null],['status'=>'Planned','deleted_at'=>null],['status'=>'Cancelled','deleted_at'=>null],['status'=>'Completed','deleted_at'=>'2026-01-01']]);
        $s=$this->snapshot(['auditmanagement']);
        $this->assertSame(50.0,$s['pillars']['G']['score']);
        $this->assertSame(1.5,$s['score']); $this->assertSame(3.0,$s['coverage']);
        $this->assertSame(2,$s['indicators'][10]['total']);
        $this->assertSame(100,array_sum(array_column($s['pillars'],'weight')));
    }
    public function testNonComplianceBlocksQualificationAndBlankStatesEarnNoCredit()
    {
        $this->table('compliance','comp_status');
        foreach (['Complied','Not Complied',null] as $state) DB::table('compliance')->insert(['comp_status'=>$state]);
        $s=$this->snapshot(['compliance']);
        $this->assertCount(1,$s['blockers']); $this->assertStringContainsString('Compliance review required',$s['status']);
        $this->assertSame(33.3,$s['indicators'][9]['score']);$this->assertSame(1,$s['indicators'][9]['unknown']);
        $this->assertNull($this->snapshot([])['score']);
    }
    public function testEveryPillarWeightsSumTo100AndAllModulesAreAccountedFor()
    {
        $config=require config_path('esg_score.php');
        foreach (['E','S','G'] as $key) $this->assertSame(100,array_sum(array_map(function($i)use($key){return $i[1]===$key?$i[6]:0;},$config['indicators'])));
        $this->assertCount(24,$this->snapshot()['modules']);
    }
    public function testScorePageRequiresAuthentication()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        $this->getJson('/user/score');
    }
    public function testAuthenticatedControllerCanExportPrivateVersionedEvidenceSummary()
    {
        $this->withoutMiddleware();
        Schema::create('modenadisable', function(Blueprint $t){$t->increments('id');$t->integer('compliance');});
        DB::table('modenadisable')->insert(['compliance'=>1]);
        $this->table('compliance','comp_status');
        DB::table('compliance')->insert(['comp_status'=>'Not Complied']);
        $response=$this->get('/user/score?export=csv');
        $response->assertOk();
        $this->assertStringContainsString('no-store',$response->headers->get('Cache-Control'));
        $this->assertStringContainsString('organization-esg-readiness.csv',$response->headers->get('Content-Disposition'));
        $content=$response->streamedContent();
        $this->assertStringContainsString('Model 1.0',$content);
        $this->assertStringContainsString('Compliance review required',$content);
        $this->assertStringContainsString('Legal compliance',$content);
    }
    public function testViewHasTransparentRulesAndNoInventedGlobalOrHistoricalScore()
    {
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
        $html=view('user.userscore',['scorecard'=>$this->snapshot(),'model'=>null])->render();
        $this->assertStringContainsString('Evidence-adjusted readiness',$html);
        $this->assertStringContainsString('organizational improvement',$html);
        $this->assertStringNotContainsString('10.1373',$html);
        $this->assertStringContainsString('GHG inventory',$html);
        if (getenv('ESG_PREVIEW_PATH')) file_put_contents(getenv('ESG_PREVIEW_PATH'), $html);
    }
}
