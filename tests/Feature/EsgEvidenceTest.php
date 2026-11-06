<?php
namespace Tests\Feature;
use App\Services\EsgEvidenceService;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
class EsgEvidenceTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();Storage::fake('local');
        require_once database_path('migrations/2026_11_06_210000_create_esg_evidence_tables.php');(new \CreateEsgEvidenceTables)->up();
        Schema::create('users',function(Blueprint $t){$t->increments('id');$t->string('name');$t->string('email');$t->softDeletes();});
        DB::table('users')->insert([['id'=>1,'name'=>'Alex','email'=>'alex@example.test'],['id'=>2,'name'=>'Reviewer','email'=>'reviewer@example.test']]);
        Schema::create('user_gates',function(Blueprint $t){$t->increments('id');$t->integer('users_id');$t->string('key');});
        $this->actingAs(User::find(1));
    }
    private function data($extra=[]){return array_merge(['kind'=>'emission','title'=>'Electricity','site'=>'Plant A','period_start'=>'2025-01-01','period_end'=>'2025-01-31','source_reference'=>'invoice-001','pillar'=>'E','scope'=>'2','basis'=>'location','quantity'=>'1000','unit'=>'kWh','factor'=>'0.4','factor_reference'=>'Published grid factor, 2025'], $extra);}
    private function source(){return UploadedFile::fake()->create('source.pdf',4,'application/pdf');}
    private function insert($extra=[]){$s=new EsgEvidenceService;return $s->insert($s->validate($this->data($extra)),'esg-evidence/source.pdf',str_repeat('a',64),$s->actor());}
    public function testCalculationAndIndependentReviewInventoryRemainSeparateFromReadiness()
    {
        $id=$this->insert();$s=new EsgEvidenceService;
        $this->assertSame([], $s->inventory());
        $record=DB::table('esg_evidence')->find($id);$this->assertSame(0.4,json_decode($record->payload,true)['tco2e']);
        DB::table('esg_evidence')->where('id',$id)->update(['status'=>'accepted']);
        $this->insert(['basis'=>'market','factor'=>'0.2']);
        $this->assertCount(1,$s->inventory());$this->assertSame(0.4,$s->inventory()[0]['total']);
        DB::table('esg_evidence')->where('id',2)->update(['status'=>'accepted']);
        $this->assertCount(2,$s->inventory());
        $this->assertSame(0.2,$s->inventory()[1]['total']);
        $this->assertDatabaseHas('esg_evidence_events',['evidence_id'=>$id,'event'=>'submitted']);
    }
    public function testBasisMismatchAndFuturePeriodAreRejected()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new EsgEvidenceService)->validate($this->data(['scope'=>'1','basis'=>'location','period_end'=>'2099-01-01']));
    }
    public function testAuthenticatedUploadStoresPrivatePendingDocumentAndPreventsDuplicate()
    {
        $this->withoutMiddleware();
        $this->post('/user/score/evidence',array_merge($this->data(),['document'=>$this->source()]))->assertRedirect('/user/score/evidence');
        $record=DB::table('esg_evidence')->first();$this->assertSame('pending',$record->status);Storage::disk('local')->assertExists($record->file_path);
        $this->post('/user/score/evidence',array_merge($this->data(),['document'=>$this->source()]))->assertSessionHasErrors('document');
        $this->assertSame(1,DB::table('esg_evidence')->count());$this->assertCount(1,Storage::disk('local')->allFiles());
    }
    public function testReviewerCannotReviewOwnSubmissionAndUnprivilegedUserCannotReview()
    {
        $this->withoutMiddleware();$this->withoutExceptionHandling();$id=$this->insert();
        DB::table('user_gates')->insert(['users_id'=>1,'key'=>'manage-esg-evidence']);
        try{$this->post('/user/score/evidence/'.$id.'/review',['decision'=>'accepted','note'=>'Checked']);$this->fail('Self-review accepted');}
        catch(\Symfony\Component\HttpKernel\Exception\HttpException $e){$this->assertSame(403,$e->getStatusCode());}
        $this->actingAs(User::find(2));
        try{$this->post('/user/score/evidence/'.$id.'/review',['decision'=>'accepted','note'=>'Checked']);$this->fail('Unauthorized review');}
        catch(\Symfony\Component\HttpKernel\Exception\HttpException $e){$this->assertSame(403,$e->getStatusCode());}
        $this->assertDatabaseHas('esg_evidence',['id'=>$id,'status'=>'pending']);
    }
    public function testDifferentAuthorizedReviewerRecordsAssuranceAndAuditEvent()
    {
        $this->withoutMiddleware();$id=$this->insert();$this->actingAs(User::find(2));DB::table('user_gates')->insert(['users_id'=>2,'key'=>'manage-esg-evidence']);
        $this->post('/user/score/evidence/'.$id.'/review',['decision'=>'accepted','note'=>'Source and factor checked','assurance_provider'=>'Independent assurance body','assurance_reference'=>'Report-2025','assurance'=>$this->source()])->assertRedirect('/user/score/evidence');
        $record=DB::table('esg_evidence')->find($id);$this->assertSame('accepted',$record->status);$this->assertNotNull($record->assurance_path);
        $this->assertDatabaseHas('esg_evidence_events',['evidence_id'=>$id,'event'=>'accepted']);
        $this->assertCount(1,(new EsgEvidenceService)->inventory());
        $this->get('/user/score/evidence/'.$id.'/download?assurance=1')->assertOk()->assertHeader('X-Content-Type-Options','nosniff');
    }
    public function testAssuranceAttachmentCannotBeRecordedWithoutProviderIdentity()
    {
        $this->withoutMiddleware();$id=$this->insert();$this->actingAs(User::find(2));DB::table('user_gates')->insert(['users_id'=>2,'key'=>'manage-esg-evidence']);
        $this->withoutExceptionHandling();
        try {$this->post('/user/score/evidence/'.$id.'/review',['decision'=>'accepted','note'=>'Checked','assurance'=>$this->source()]);$this->fail('Assurance accepted without provider');}
        catch(\Illuminate\Validation\ValidationException $e){$this->assertArrayHasKey('assurance_provider',$e->errors());$this->assertArrayHasKey('assurance_reference',$e->errors());}
        $this->assertDatabaseHas('esg_evidence',['id'=>$id,'status'=>'pending']);
    }
    public function testImportIsAtomicAndAlwaysPending()
    {
        $this->withoutMiddleware();$data=$this->data();$handle=fopen('php://temp','r+');fputcsv($handle,array_keys($data));fputcsv($handle,array_values($data));fputcsv($handle,array_values($data));rewind($handle);$csv=stream_get_contents($handle);fclose($handle);
        $this->post('/user/score/evidence/import',['csv'=>UploadedFile::fake()->createWithContent('data.csv',$csv),'document'=>$this->source()])->assertSessionHasErrors('csv');
        $this->assertSame(0,DB::table('esg_evidence')->count());$this->assertSame(0,DB::table('esg_evidence_events')->count());
        $csv=implode(',',array_keys($data))."\n".implode(',',array_values(array_merge($data,['factor_reference'=>'Grid factor 2025'])))."\n";
        $this->post('/user/score/evidence/import',['csv'=>UploadedFile::fake()->createWithContent('data.csv',$csv),'document'=>$this->source()])->assertRedirect('/user/score/evidence');
        $this->assertDatabaseHas('esg_evidence',['status'=>'pending']);
    }
    public function testWithdrawalRemovesAcceptedInventoryAndPreservesSourceAndAuditHistory()
    {
        $this->withoutMiddleware();$id=$this->insert();DB::table('esg_evidence')->where('id',$id)->update(['status'=>'accepted']);
        $this->assertCount(1,(new EsgEvidenceService)->inventory());
        $this->post('/user/score/evidence/'.$id.'/withdraw',['note'=>'Corrected supplier factor issued'])->assertRedirect('/user/score/evidence');
        $this->assertSame([],(new EsgEvidenceService)->inventory());
        $this->assertDatabaseHas('esg_evidence',['id'=>$id,'status'=>'withdrawn','source_reference'=>'invoice-001']);
        $this->assertDatabaseHas('esg_evidence_events',['evidence_id'=>$id,'event'=>'withdrawn','note'=>'Corrected supplier factor issued']);
    }
    public function testEvidenceRoutesRequireAuthentication()
    {
        auth()->logout();$this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);$this->getJson('/user/score/evidence/1/download');
    }
    public function testRegisterRendersSafelyAndPreservesEvidenceLabels()
    {
        $this->withoutMiddleware();$this->withoutExceptionHandling();view()->share('errors',new \Illuminate\Support\ViewErrorBag);view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));app('events')->forget('composing: *');
        $this->insert(['title'=>'<script>alert(1)</script>']);
        $response=$this->get('/user/score/evidence');$response->assertOk()->assertSee('Evidence & emissions',false)->assertSee('&lt;script&gt;',false)->assertDontSee('<script>alert(1)</script>',false);
        if(getenv('ESG_EVIDENCE_PREVIEW_PATH'))file_put_contents(getenv('ESG_EVIDENCE_PREVIEW_PATH'),$response->getContent());
    }
}
