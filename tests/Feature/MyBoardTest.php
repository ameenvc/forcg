<?php
namespace Tests\Feature;

use App\Services\MyBoard\MyBoardService;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MyBoardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
        config(['app.timezone' => 'UTC']);
        Carbon::setTestNow(Carbon::parse('2026-11-06 12:00:00', 'UTC'));
        Schema::create('users', function (Blueprint $t) { $t->increments('id'); $t->string('name'); $t->string('email'); $t->string('employeeid')->nullable(); $t->softDeletes(); });
        DB::table('users')->insert([['id'=>1,'name'=>'Alex Morgan','email'=>'alex@example.test','employeeid'=>'EMP1'],['id'=>11,'name'=>'Jamie Reed','email'=>'jamie@example.test','employeeid'=>'EMP11']]);
    }
    protected function tearDown(): void { Carbon::setTestNow(); parent::tearDown(); }
    private function user() { return User::findOrFail(1); }
    private function service() { return new MyBoardService(); }
    private function only(array $sources): void
    {
        $settings = require config_path('my_board.php');
        $settings['sources'] = array_intersect_key($settings['sources'], array_flip($sources));
        config(['my_board' => $settings]);
    }
    private function mitigationTable(): void
    {
        Schema::create('mitigations', function (Blueprint $t) {
            $t->increments('id'); $t->string('owner'); $t->string('action_name'); $t->string('action_id'); $t->string('status');
            $t->string('due_date')->nullable(); $t->string('progress')->default('0'); $t->string('reviewed_date')->nullable();
            $t->integer('reviewed_by')->nullable(); $t->integer('created_by')->nullable(); $t->string('updated_at')->nullable();
        });
    }
    private function action(array $fields): int
    {
        return DB::table('mitigations')->insertGetId(array_merge(['owner'=>'1','action_name'=>'Improve controls','action_id'=>'OPE1/1/26','status'=>'Open','due_date'=>'2026-11-10','updated_at'=>'2026-11-06 10:00:00'], $fields));
    }
    public function testAuditAssignmentsShowDepartmentInsteadOfStandardsInTasksAndUpdates()
    {
        $this->only(['audit_schedules']);
        Schema::create('departments', function (Blueprint $t) { $t->increments('id'); $t->string('departmentname'); });
        Schema::create('audit', function (Blueprint $t) {
            $t->increments('id'); $t->integer('department')->nullable(); $t->string('scope');
            $t->string('aud_id'); $t->string('email'); $t->string('status'); $t->string('updated_at');
        });
        DB::table('departments')->insert(['id'=>7,'departmentname'=>'Warehouse']);
        foreach ([7,999,null] as $department) DB::table('audit')->insert([
            'department'=>$department,'scope'=>'ISO 9001:2015, ISO 14001:2015','aud_id'=>'WHS1/26',
            'email'=>'alex@example.test','status'=>'Planned','updated_at'=>'2026-11-06 10:00:00',
        ]);
        $board = $this->service()->snapshot($this->user(), ['auditmanagement']);
        $this->assertSame(['Warehouse audit','Department audit','Department audit'], array_column($board['tasks'], 'title'));
        $this->assertContains('Warehouse audit', array_column($board['updates'], 'title'));
        $this->assertStringNotContainsString('ISO 9001', json_encode($board));
        $this->assertSame('WHS1/26', $board['tasks'][0]['reference']);
        $this->assertSame('Continue audit', $board['tasks'][0]['action_label']);
        $this->assertSame(url('/user/internalaudit/update').'?val=1', $board['tasks'][0]['action_url']);
    }
    public function testPersonalActionsMatchExactIdsAndJsonListsWithoutSubstringLeaks()
    {
        $this->only(['mitigations']); $this->mitigationTable();
        $this->action(['action_name'=>'Mine']); $this->action(['owner'=>'[1,11]','action_name'=>'Shared']);
        $this->action(['owner'=>'11','action_name'=>'Private other work']); $this->action(['owner'=>'111','action_name'=>'Private another work']);
        $board = $this->service()->snapshot($this->user(), ['riskmanagement']);
        $this->assertSame(2, $board['counts']['all']);
        $this->assertSame(['Mine','Shared'], array_column($board['tasks'],'title'));
        $this->assertStringNotContainsString('Private other', json_encode($board));
    }
    public function testScoreUsesActualCompletionsAndKnownDatesRatherThanVisitsOrPlaceholderPoints()
    {
        $this->only(['mitigations']); $this->mitigationTable();
        $this->action(['status'=>'Closed','due_date'=>'2026-11-05','reviewed_date'=>'2026-11-04']);
        $this->action(['due_date'=>'2026-11-01']);
        $this->action(['due_date'=>'2026-11-08']);
        $board = $this->service()->snapshot($this->user(), ['riskmanagement']);
        $this->assertSame(2, $board['counts']['all']); $this->assertSame(1,$board['counts']['overdue']); $this->assertSame(1,$board['counts']['due_soon']);
        $this->assertSame(1,$board['score']['completed']); $this->assertSame(1,$board['score']['on_time']);
        $this->assertSame(38,$board['score']['value']); // 70*(1/3) + 30*(1/2)
        $this->assertSame($board['score'], $this->service()->snapshot($this->user(), ['riskmanagement'])['score']);
    }
    public function testEmptyAndUnavailableModulesAreExplicitAndNeverGetAFreePerfectScore()
    {
        $this->only(['mitigations']);
        $board = $this->service()->snapshot($this->user(), ['riskmanagement','occhealth']);
        $this->assertSame([], $board['tasks']); $this->assertNull($board['score']['value']);
        $this->assertSame(0,$board['coverage']['connected']);
        $this->assertSame(['unavailable'],array_values(array_unique(array_column($board['modules'],'state'))));
    }
    public function testDisabledModulesAreNotQueriedOrExposed()
    {
        $this->only(['mitigations']); $this->mitigationTable(); $this->action([]);
        $board = $this->service()->snapshot($this->user(), []);
        $this->assertSame([], $board['tasks']); $this->assertSame([], $board['modules']); $this->assertSame([], $board['updates']);
    }
    public function testLegacyDatesAreStrictAndUnknownCompletionDatesDoNotInventOnTimeCredit()
    {
        $this->only(['mitigations']); $this->mitigationTable();
        $this->action(['due_date'=>'31/02/2026']); $this->action(['status'=>'Closed','due_date'=>'2026-11-05','reviewed_date'=>null]);
        $board = $this->service()->snapshot($this->user(), ['riskmanagement']);
        $this->assertNull($board['tasks'][0]['due']); $this->assertSame(0,$board['score']['dated_completed']);
        $this->assertNull($board['score']['timeliness_percent']); $this->assertSame(50,$board['score']['value']);
        $this->assertSame('2026-11-06', $this->service()->date('06-11-2026 10:30:00 AM')->toDateString());
        $this->assertNull($this->service()->date('next Monday')); $this->assertNull($this->service()->date('0000-00-00'));
    }
    public function testRiskReviewerUsesUserIdNotDirectoryIdAndCompletedReviewsAreNotPending()
    {
        $this->only(['risks','criteria']);
        Schema::create('riskreviewers', function (Blueprint $t) { $t->increments('id'); $t->string('email'); });
        DB::table('riskreviewers')->insert([['id'=>31,'email'=>'alex@example.test'],['id'=>1,'email'=>'jamie@example.test']]);
        Schema::create('risks', function (Blueprint $t) { $t->increments('Rid'); $t->string('riskname'); $t->string('grp_id'); $t->string('action_status'); $t->integer('riskreviewer_id'); $t->softDeletes(); });
        DB::table('risks')->insert([['riskname'=>'Mine to review','grp_id'=>'STR1','action_status'=>'Under review','riskreviewer_id'=>1],['riskname'=>'Private other review','grp_id'=>'STR2','action_status'=>'Under review','riskreviewer_id'=>31]]);
        Schema::create('risk_racms', function (Blueprint $t) { $t->increments('id'); $t->string('risk_factor_title'); $t->string('status'); $t->integer('approver_id')->nullable(); });
        Schema::create('racm_reviewer_details', function (Blueprint $t) { $t->increments('id'); $t->integer('racm_id'); $t->integer('reviewer_id'); $t->boolean('reviewed_status'); $t->boolean('racm_rejected'); $t->string('reviewed_date')->nullable(); });
        DB::table('risk_racms')->insert([['id'=>1,'risk_factor_title'=>'Threshold one','status'=>'Review Requested'],['id'=>2,'risk_factor_title'=>'Threshold two','status'=>'Review Requested']]);
        DB::table('racm_reviewer_details')->insert([['racm_id'=>1,'reviewer_id'=>1,'reviewed_status'=>false,'racm_rejected'=>false,'reviewed_date'=>null],['racm_id'=>2,'reviewer_id'=>1,'reviewed_status'=>true,'racm_rejected'=>false,'reviewed_date'=>'2026-11-05']]);
        $board = $this->service()->snapshot($this->user(), ['riskmanagement']);
        $this->assertSame(2,$board['counts']['review']); $this->assertSame(1,$board['score']['completed']);
        $this->assertStringNotContainsString('Private other',json_encode($board));
        $this->assertContains(url('/user/riskmgmt/racm/review/1/1'),array_column($board['tasks'],'url'));
    }
    public function testAmbiguousLegacyNamesAreNotUsedToExposeAnotherUsersAssignments()
    {
        $this->only(['maintenance']);
        Schema::create('maintenance', function (Blueprint $t) { $t->increments('id'); $t->string('desc'); $t->string('pmby'); $t->string('pmstatus'); $t->string('schedule'); });
        DB::table('maintenance')->insert(['desc'=>'Inspect equipment','pmby'=>'Alex Morgan','pmstatus'=>'Time for PM','schedule'=>'2026-11-10']);
        $this->assertSame(1,$this->service()->snapshot($this->user(),['maintainance'])['counts']['all']);
        DB::table('users')->insert(['name'=>'Alex Morgan','email'=>'another@example.test','employeeid'=>'OTHER']);
        $board = $this->service()->snapshot($this->user(), ['maintainance']);
        $this->assertSame(0,$board['counts']['all']); $this->assertSame('unavailable',$board['modules'][0]['state']);
    }
    public function testDeletedRecordsAndUnassignedRecordsDoNotEnterTheInbox()
    {
        $this->only(['risks']);
        Schema::create('risks', function (Blueprint $t) { $t->increments('Rid'); $t->string('riskname'); $t->string('risk_owner'); $t->string('action_status'); $t->softDeletes(); });
        DB::table('risks')->insert([['riskname'=>'Deleted risk','risk_owner'=>'alex@example.test','action_status'=>'Awaiting Periodic Review','deleted_at'=>'2026-11-01'],['riskname'=>'Not my risk','risk_owner'=>'otheralex@example.test','action_status'=>'Awaiting Periodic Review','deleted_at'=>null]]);
        $board = $this->service()->snapshot($this->user(), ['riskmanagement']); $this->assertSame([], $board['tasks']); $this->assertSame([], $board['updates']);
    }
    public function testBoardViewAndJsonArePersonalAndDoNotUseMockCounts()
    {
        $this->only(['mitigations']); $this->mitigationTable(); $this->action(['action_name'=>'<script>alert(1)</script>Control improvement']);
        Schema::create('modenadisable',function (Blueprint $t) { $t->increments('id'); $t->boolean('riskmanagement')->default(true); });
        $this->actingAs($this->user());
        $service=$this->service(); $request=\Illuminate\Http\Request::create('/user/my-board');
        $response=app(\App\Http\Controllers\UserController::class)->myBoard($request,$service);
        $this->assertSame(200,$response->getStatusCode()); $html=$response->getContent();
        $this->assertStringContainsString('Your action inbox',$html); $this->assertStringContainsString('Contribution score',$html);
        $this->assertStringNotContainsString('<script>alert(1)</script>',$html); $this->assertStringNotContainsString('Redeem Points',$html);
        $jsonRequest=\Illuminate\Http\Request::create('/user/my-board','GET',[],[],[],['HTTP_ACCEPT'=>'application/json']);
        $json=app(\App\Http\Controllers\UserController::class)->myBoard($jsonRequest,$this->service());
        $this->assertSame(1,$json->getData(true)['counts']['all']); $this->assertStringContainsString('no-store',$json->headers->get('Cache-Control'));
        if (getenv('MY_BOARD_PREVIEW_PATH')) file_put_contents(getenv('MY_BOARD_PREVIEW_PATH'),$html);
    }
    public function testEveryAssignedItemRemainsAvailableForPagingAndPriorityOrdering()
    {
        $this->only(['mitigations','criteria','corrective']); $this->mitigationTable();
        Schema::table('mitigations', function (Blueprint $t) { $t->string('priority_level')->nullable(); });
        foreach (range(1, 42) as $number) {
            $this->action(['action_name' => ['Review supplier continuity plan','Upgrade preventive maintenance controls','Confirm energy meter calibration','Document cybersecurity safeguards','Complete emergency drill follow-up','Validate corrective-action evidence'][$number % 6],
                'action_id' => 'OPE'.(170 + $number).'/1/26', 'due_date' => $number % 3 === 0 ? '2026-11-02' : '2026-11-10',
                'priority_level' => $number % 4 === 0 ? 'high' : 'medium', 'status' => $number > 35 ? 'Closed' : 'Open',
                'reviewed_date' => $number > 35 ? '2026-11-01' : null]);
        }
        Schema::create('risk_racms', function (Blueprint $t) { $t->increments('id'); $t->string('risk_factor_title'); $t->string('status'); $t->integer('approver_id'); });
        Schema::create('racm_reviewer_details', function (Blueprint $t) { $t->increments('id'); $t->integer('racm_id'); $t->integer('reviewer_id'); $t->boolean('reviewed_status'); $t->boolean('racm_rejected'); });
        DB::table('risk_racms')->insert([['id'=>1,'risk_factor_title'=>'Approve production-capacity thresholds','status'=>'Reviewed','approver_id'=>1], ['id'=>2,'risk_factor_title'=>'Validate environmental impact criteria','status'=>'Review Requested','approver_id'=>11]]);
        DB::table('racm_reviewer_details')->insert(['racm_id'=>2,'reviewer_id'=>1,'reviewed_status'=>false,'racm_rejected'=>false]);
        Schema::create('corrective',function (Blueprint $t) { $t->increments('id'); $t->string('title'); $t->string('issueid'); $t->string('processstage'); $t->string('correction_responsibility'); $t->string('correction_target_date'); $t->string('updated_at'); });
        DB::table('corrective')->insert([['title'=>'Resolve recurring supplier nonconformance','issueid'=>'NCR021','processstage'=>'Awaiting actions','correction_responsibility'=>'alex@example.test','correction_target_date'=>'2026-11-04','updated_at'=>'2026-11-06 11:00:00'],['title'=>'Confirm corrective action effectiveness','issueid'=>'NCR022','processstage'=>'Awaiting actions','correction_responsibility'=>'alex@example.test','correction_target_date'=>'2026-11-09','updated_at'=>'2026-11-06 10:45:00']]);
        $board=$this->service()->snapshot($this->user(), ['riskmanagement','correctiveaction','auditmanagement','compliance','elearning','hse','enviaspects','maintainance']);
        $this->assertCount(39,$board['tasks']); $this->assertSame(39,$board['counts']['all']);
        $this->assertSame(1,$board['counts']['review']); $this->assertSame(1,$board['counts']['approval']);
        $this->assertTrue($board['tasks'][0]['overdue']); $this->assertSame(7,$board['score']['completed']);
        $this->assertCount(20,$board['updates']);
        $html=view('user.myboard',['board'=>$board,'user'=>$this->user(),'model'=>null])->render();
        $this->assertSame(10,substr_count($html,'class="mb-task"'));
        $this->assertStringContainsString('my-board-data',$html);
        if (getenv('MY_BOARD_POPULATED_PREVIEW_PATH')) file_put_contents(getenv('MY_BOARD_POPULATED_PREVIEW_PATH'),$html);
    }

    public function testDocumentReviewAssignmentsExcludeAlreadyReviewedDrafts()
    {
        $this->only(['document_drafts']);
        Schema::create('governance_hub_drafts',function (Blueprint $t) { $t->increments('id'); $t->string('document_name'); $t->string('status'); $t->string('reviewer'); $t->boolean('is_deleted')->default(false); });
        Schema::create('governance_hub_drafts_reviewer_details',function (Blueprint $t) { $t->increments('id'); $t->integer('draft_id'); $t->integer('reviewer_id'); $t->boolean('reviewed_status'); $t->boolean('draft_rejected'); });
        DB::table('governance_hub_drafts')->insert([['id'=>1,'document_name'=>'Pending policy review','status'=>'Review Requested','reviewer'=>'alex@example.test'],['id'=>2,'document_name'=>'Already reviewed policy','status'=>'Review Requested','reviewer'=>'alex@example.test']]);
        DB::table('governance_hub_drafts_reviewer_details')->insert([['draft_id'=>1,'reviewer_id'=>1,'reviewed_status'=>false,'draft_rejected'=>false],['draft_id'=>2,'reviewer_id'=>1,'reviewed_status'=>true,'draft_rejected'=>false]]);
        $board=$this->service()->snapshot($this->user(),['documentmanagement']);
        $this->assertSame(1,$board['counts']['review']); $this->assertSame('Pending policy review',$board['tasks'][0]['title']); $this->assertSame(1,$board['score']['completed']);
    }

    public function testBoardRequiresAuthentication()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        $this->getJson('/user/my-board');
    }
    public function testWorkQueryCountDoesNotGrowForEveryAdditionalItem()
    {
        $this->only(['mitigations']); $this->mitigationTable(); $this->action([]);
        DB::enableQueryLog(); DB::flushQueryLog();
        $this->service()->snapshot($this->user(),['riskmanagement']); $small=count(DB::getQueryLog());
        foreach (range(1,80) as $i) $this->action(['action_name'=>'Assigned action '.$i]);
        DB::flushQueryLog(); $board=$this->service()->snapshot($this->user(),['riskmanagement']); $large=count(DB::getQueryLog()); DB::disableQueryLog();
        $this->assertCount(81,$board['tasks']); $this->assertSame($small,$large,'Assigned work must be collected in batches rather than one query per item.');
    }

    public function testTrainingUsesEmployeeRecordIdsInsteadOfConfusingThemWithUserIds()
    {
        $this->only(['training']);
        Schema::create('employee_details',function (Blueprint $t) { $t->increments('id'); $t->string('emp_number'); });
        DB::table('employee_details')->insert([['id'=>31,'emp_number'=>'EMP1'],['id'=>1,'emp_number'=>'EMP11']]);
        Schema::create('hr_training',function (Blueprint $t) { $t->increments('id'); $t->string('training_title'); $t->string('status'); $t->string('employee_id'); });
        DB::table('hr_training')->insert([['training_title'=>'My required training','status'=>'Pending','employee_id'=>'31'],['training_title'=>'Private other training','status'=>'Pending','employee_id'=>'1']]);
        $board=$this->service()->snapshot($this->user(),['hr']);
        $this->assertSame(1,$board['counts']['all']); $this->assertSame('My required training',$board['tasks'][0]['title']);
    }
    public function testDocumentApprovalUsesEmailAndOnlyRecordedApproverGetsCompletionCredit()
    {
        $this->only(['document_drafts']);
        Schema::create('governance_hub_drafts',function (Blueprint $t) { $t->increments('id'); $t->string('document_name'); $t->string('status'); $t->string('approvers'); $t->string('drafted_by')->nullable(); $t->integer('approved_by_user')->nullable(); $t->string('approved_date')->nullable(); });
        DB::table('governance_hub_drafts')->insert([['document_name'=>'Approval for me','status'=>'Draft Reviewed','approvers'=>'alex@example.test'],['document_name'=>'Private other approval','status'=>'Draft Reviewed','approvers'=>'1']]);
        DB::table('governance_hub_drafts')->insert(['document_name'=>'Recorded approval','status'=>'Draft Approved','approvers'=>'alex@example.test','drafted_by'=>'alex@example.test','approved_by_user'=>1,'approved_date'=>'2026-11-05']);
        $board=$this->service()->snapshot($this->user(),['documentmanagement']);
        $this->assertSame(1,$board['counts']['approval']); $this->assertSame('Approval for me',$board['tasks'][0]['title']);
        $this->assertSame(1,$board['score']['completed'],'The author must not earn another completion merely for adding the approved draft.');
    }
    public function testGlobalReviewScopeDoesNotLeakOtherPeoplesClosedRecordsIntoPersonalUpdates()
    {
        $this->only(['mitigations']); $this->mitigationTable();
        Schema::create('riskreviewers',function (Blueprint $t) { $t->increments('id'); $t->string('email'); });
        DB::table('riskreviewers')->insert(['email'=>'alex@example.test']);
        $this->action(['owner'=>'11','status'=>'Closed','progress'=>100,'action_name'=>'Unrelated closed work']);
        $this->action(['owner'=>'11','status'=>'Open','progress'=>100,'action_name'=>'Awaiting my review']);
        $board=$this->service()->snapshot($this->user(),['riskmanagement']);
        $this->assertSame(1,$board['counts']['review']); $this->assertSame(0,$board['score']['completed']);
        $this->assertSame(['Awaiting my review'],array_column($board['updates'],'title'));
    }

    public function testOutstandingApprovalIsIncludedInTheContributionScoreDenominator()
    {
        $this->only(['criteria']);
        Schema::create('risk_racms',function (Blueprint $t) { $t->increments('id'); $t->string('risk_factor_title'); $t->string('status'); $t->integer('approver_id'); $t->integer('approved_by')->nullable(); $t->string('approved_date')->nullable(); });
        DB::table('risk_racms')->insert(['risk_factor_title'=>'Approval still pending','status'=>'Reviewed','approver_id'=>1]);
        DB::table('risk_racms')->insert(['risk_factor_title'=>'Recorded approval','status'=>'Approved','approver_id'=>1,'approved_by'=>1,'approved_date'=>'2026-11-05']);
        $board=$this->service()->snapshot($this->user(),['riskmanagement']);
        $this->assertSame(1,$board['score']['pending']); $this->assertSame(1,$board['score']['completed']); $this->assertSame(50,$board['score']['value']);
    }

    public function testRoleBasedReviewFeedUsesTheModulesActualUserGate()
    {
        $this->only(['hse_observations']);
        Schema::create('user_gates',function (Blueprint $t) { $t->increments('id'); $t->string('key'); $t->integer('users_id'); });
        Schema::create('hseissues',function (Blueprint $t) { $t->increments('id'); $t->string('title'); $t->string('issueid'); $t->string('processstage'); });
        DB::table('hseissues')->insert(['title'=>'Review safety observation','issueid'=>'HSE1','processstage'=>'Entry Under Review']);
        DB::table('user_gates')->insert(['key'=>'hse-observation-reviewer','users_id'=>11]);
        $this->assertSame(0,$this->service()->snapshot($this->user(),['hse'])['counts']['review']);
        DB::table('user_gates')->insert(['key'=>'hse-observation-reviewer','users_id'=>1]);
        $this->assertSame(1,$this->service()->snapshot($this->user(),['hse'])['counts']['review']);
    }

    public function testCorrectiveDeadlineFollowsTheUsersOwnPositionInTheAssignmentList()
    {
        $this->only(['corrective']);
        Schema::create('corrective',function (Blueprint $t) { $t->increments('id'); $t->string('title'); $t->string('processstage'); $t->string('responsibility'); $t->string('target_date')->nullable(); $t->string('correction_target_date')->nullable(); });
        DB::table('corrective')->insert(['title'=>'My root-cause action','processstage'=>'Action Reviewed, OK','responsibility'=>'jamie@example.test,alex@example.test','target_date'=>'2026-11-02,2026-11-12','correction_target_date'=>'2026-11-01']);
        $board=$this->service()->snapshot($this->user(),['correctiveaction']);
        $this->assertSame('2026-11-12',$board['tasks'][0]['due']); $this->assertFalse($board['tasks'][0]['overdue']);
    }

}
