<?php
namespace Tests\Feature;

use App\Risk;
use App\RiskHistory;
use App\User;
use App\Http\Controllers\RiskController;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RiskRegisterHistoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->getFinder()->prependLocation(base_path('tests/Fixtures/views'));
        app('events')->forget('composing: *');
    }

    public function testHistoryShowsActualBeforeAfterValuesUnknownRatingsAndEmptyRecords()
    {
        $risk = (new Risk)->forceFill(['Rid'=>208, 'grp_id'=>'OPE208', 'riskname'=>'Critical supplier concentration and disruption across regional production facilities', 'riskgroup'=>'Operational', 'riskdec'=>'<script>alert(1)</script>Supplier concentration', 'rank'=>'Low']);
        $risk->setRelation('department', (object) ['departmentname'=>'Operations']);
        $events = collect();
        foreach ([
            ['id'=>3,'action'=>'Periodic Review','impa'=>'High','impa_updated'=>'Moderate','rank'=>3,'rank_updated'=>2,'mitigating_action'=>'<script>alert(1)</script>','mitigating_action_updated'=>'Report weekly with process owner','created_at'=>'2026-11-05 17:30:00'],
            ['id'=>2,'action'=>null,'rank'=>9,'created_at'=>'2026-10-02 10:00:00'],
            ['id'=>1,'action'=>'Intial Review','created_at'=>'2026-09-01 09:00:00'],
        ] as $attributes) {
            $event = (new RiskHistory)->forceFill($attributes);
            $event->setRelation('riskOwner', $attributes['id'] === 2 ? null : (new User)->forceFill(['name'=>'Morgan Reed']));
            $events->push($event);
        }
        $html = view('user.risks.riskhistory', ['riskdata'=>$risk,'riskhistory'=>$events,'model'=>null])->render();
        foreach (['Risk history','Recorded activity','Initial Review','Morgan Reed','rh-tone-neutral">9','Recorded value','No assessment changes were recorded','history-search','history-event-type','history-sort'] as $text) $this->assertStringContainsString($text, $html);
        $this->assertStringContainsString(route('risk.detail',208), $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringNotContainsString('bg-info text-white', $html);
        $this->assertStringNotContainsString('@else', $html);
        if (getenv('RISK_HISTORY_PREVIEW_PATH')) file_put_contents(getenv('RISK_HISTORY_PREVIEW_PATH'), $html);
        $empty = view('user.risks.riskhistory', ['riskdata'=>$risk,'riskhistory'=>collect(),'model'=>null])->render();
        $this->assertStringContainsString('No history recorded yet', $empty);
        $this->assertStringContainsString('OPE208', $empty);
        $this->assertStringContainsString('Critical supplier concentration', $empty);
    }

    public function testHistoryControllerScopesAndOrdersEventsAndRejectsInvalidRecords()
    {
        Schema::create('risks', function (Blueprint $table) { $table->increments('Rid'); $table->integer('riskarea')->nullable(); $table->softDeletes(); });
        Schema::create('departments', function (Blueprint $table) { $table->increments('id'); });
        Schema::create('users', function (Blueprint $table) { $table->increments('id'); $table->string('name')->nullable(); $table->softDeletes(); });
        Schema::create('modenadisable', function (Blueprint $table) { $table->increments('id'); });
        Schema::create('riskhistory', function (Blueprint $table) { $table->increments('id'); $table->integer('Rid'); $table->integer('risk_owner_id')->nullable(); $table->timestamp('created_at')->nullable(); });
        DB::table('risks')->insert(['Rid'=>208]);
        DB::table('users')->insert(['id'=>1,'name'=>'Archived reviewer','deleted_at'=>'2026-01-01']);
        DB::table('riskhistory')->insert([
            ['id'=>1,'Rid'=>208,'risk_owner_id'=>1,'created_at'=>'2026-10-01 10:00:00'],
            ['id'=>2,'Rid'=>208,'risk_owner_id'=>1,'created_at'=>'2026-11-01 10:00:00'],
            ['id'=>3,'Rid'=>999,'risk_owner_id'=>null,'created_at'=>'2026-11-05 10:00:00'],
        ]);
        $controller = app(RiskController::class);
        $view = $controller->riskhistory(Request::create('/user/risks/riskhistory','GET',['val'=>208]));
        $events = $view->getData()['riskhistory'];
        $this->assertSame([2,1], $events->pluck('id')->all());
        $this->assertSame('Archived reviewer', $events->first()->riskOwner->name);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $controller->riskhistory(Request::create('/user/risks/riskhistory','GET',['val'=>'bad']));
    }
}
