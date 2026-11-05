<?php

namespace Tests\Feature;

use App\Helpers\Risk\NewRiskAddedEmailHelper;
use App\Mail\NewRiskAdded;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RiskSubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('employeeid');
            $table->string('password')->nullable();
            $table->string('last_seen')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('app_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meta_key');
            $table->string('meta_value')->nullable();
        });
        Schema::create('module_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('module_name');
            $table->timestamp('last_accessed_at');
            $table->timestamps();
        });
        Schema::create('risks', function (Blueprint $table) {
            $table->increments('Rid');
            $table->integer('count');
            foreach (['grp_id', 'riskgroup', 'riskarea', 'riskname', 'riskscr', 'date', 'impa', 'like', 'rank', 'postedby', 'risk_response'] as $field) {
                $table->string($field)->nullable();
            }
            foreach (['riskdec', 'riskdri', 'existing_controls'] as $field) {
                $table->text($field)->nullable();
            }
            $table->integer('riskreviewer_id');
            $table->integer('division_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('departmentname');
        });
        Schema::create('viewactivity', function (Blueprint $table) {
            $table->increments('id');
            foreach (['name', 'email', 'date', 'time', 'activity', 'type'] as $field) {
                $table->string($field);
            }
            $table->timestamps();
        });
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module');
            $table->string('item');
            $table->boolean('is_blocked');
            $table->text('email_content');
        });
        Schema::create('email_notifications_user_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notification_id');
            $table->integer('user_id');
        });
        Schema::create('modenadisable', function (Blueprint $table) { $table->increments('id'); });
        Schema::create('error_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('error');
            $table->string('logged_date');
            $table->integer('solved');
            $table->integer('occurence');
        });
        DB::table('departments')->insert(['id' => 1, 'departmentname' => 'Maintenance']);
        DB::table('email_notifications')->insert(['id' => 1, 'module' => 'riskmanagement', 'item' => 'New Risk Added', 'is_blocked' => false, 'email_content' => 'Review the risk.']);
        $user = new User(['name' => 'Reviewer', 'email' => 'reviewer@example.test', 'employeeid' => 'TEST1']);
        $user->save();
        $this->actingAs($user);
    }

    private function payload()
    {
        return ['riskgroup' => 'Operational', 'riskarea' => '1', 'riskname' => 'Critical spare-part shortages',
            'riskdec' => 'Spare-part shortages may interrupt production.', 'riskdri' => 'Single supplier',
            'existing_controls' => 'Stock reviews', 'riskreviewer_id' => 1, 'impa' => 'High',
            'like' => 'Moderate', 'risk_response' => 'Mitigate'];
    }

    public function testSmtpRejectionPreservesSavedRiskAndShowsWarning()
    {
        Log::spy();
        Mail::shouldReceive('to')->once()->with('reviewer@example.test')->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(new \Swift_TransportException('554 Outbound sending disabled', 554));
        $response = $this->post('/user/risks/submit', $this->payload());
        $response->assertRedirect(route('risk.index'))
            ->assertSessionHas('risk_notification_warning')
            ->assertSessionHas('msg', 'Risk saved successfully.')
            ->assertSessionMissing('errmsg');
        $this->assertSame(1, DB::table('risks')->count());
        $this->assertDatabaseHas('risks', ['riskname' => 'Critical spare-part shortages', 'rank' => 'High', 'riskreviewer_id' => 1, 'grp_id' => 'OPE1']);
        // Render the warning itself; the unrelated page-wide composer requires
        // the complete application database, not these isolated fixtures.
        $html = app('view.engine.resolver')->resolve('blade')->get(
            resource_path('views/user/risks/notification-warning.blade.php'), []
        );
        $this->assertStringContainsString('Risk saved.', $html);
        $this->assertStringContainsString('do not submit the risk again', $html);
        Log::shouldHaveReceived('warning')->once()->with('Risk notification transport failed.', ['risk_id' => 1, 'transport_code' => 554]);
    }

    public function testSuccessfulSubmissionStillAttemptsTheReviewEmail()
    {
        Mail::fake();
        $this->post('/user/risks/submit', $this->payload())->assertRedirect()
            ->assertSessionMissing('risk_notification_warning');
        $this->assertSame(1, DB::table('risks')->count());
        Mail::assertSent(NewRiskAdded::class, function ($mail) { return $mail->hasTo('reviewer@example.test'); });
    }

    public function testDisabledNotificationsDoNotPreventSaving()
    {
        Mail::fake();
        DB::table('email_notifications')->update(['is_blocked' => true]);
        $this->post('/user/risks/submit', $this->payload())->assertRedirect(route('risk.index'))
            ->assertSessionHas('risk_notification_warning');
        $this->assertSame(1, DB::table('risks')->count());
        Mail::assertNothingSent();
    }

    public function testLaterRecipientFailureDoesNotRetryEarlierRecipients()
    {
        Log::spy();
        $extra = new User(['name' => 'Additional recipient', 'email' => 'additional@example.test', 'employeeid' => 'TEST2']);
        $extra->save();
        DB::table('email_notifications_user_details')->insert(['notification_id' => 1, 'user_id' => $extra->id]);
        Mail::shouldReceive('to')->once()->with('reviewer@example.test')->andReturnSelf();
        Mail::shouldReceive('to')->once()->with(['additional@example.test'])->andReturnSelf();
        $attempts = 0;
        Mail::shouldReceive('send')->twice()->andReturnUsing(function () use (&$attempts) {
            if (++$attempts === 2) throw new \Swift_TransportException('554 Outbound sending disabled', 554);
        });
        $this->post('/user/risks/submit', $this->payload())->assertRedirect(route('risk.index'))
            ->assertSessionHas('risk_notification_warning');
        $this->assertSame(2, $attempts);
        $this->assertSame(1, DB::table('risks')->count());
    }

    public function testUnrelatedProgrammingErrorsAreNotHidden()
    {
        Mail::fake();
        $this->post('/user/risks/submit', $this->payload())->assertRedirect();
        Mail::shouldReceive('to')->andReturnSelf();
        Mail::shouldReceive('send')->andThrow(new \LogicException('Unexpected application error'));
        $this->expectException(\LogicException::class);
        NewRiskAddedEmailHelper::sendEmail(1);
    }
}
