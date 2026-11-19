<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CalibrationStatusUpdate::class,
        Commands\DocumentReadRateRequestEmailNotification::class,
        Commands\LicensePermitRenewalEmailNotifications::class,
        Commands\SendMailForAuditDelayCommand::class,
        Commands\RiskReminderForAddMitigationAction::class,
        Commands\RacmApprovalRequestNotification::class,
        Commands\RacmReviewRequestNotification::class,
        Commands\OpportunityIssueDueEmailRemainder::class,
        Commands\ComplianceReviewEvaluationStatusUpdate::class,
        Commands\SendCorrectiveEscalationAlertEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (config('tenancy.enabled')) {
            $schedule->command('tenants:process-operations')->everyMinute()
                ->name('tenant-operations-' . config('tenancy.node_id'))->withoutOverlapping(60);
        }
        $tenantTask = function ($command) use ($schedule) {
            return $schedule->command(config('tenancy.enabled')
                ? 'tenants:run ' . $command . ' --all' : $command);
        };

        //$schedule->command('calibrationstatusupdate:daily')->dailyAt('01:00');
        $tenantTask('documentreadrequestemailnotification:daily')->daily();
        $tenantTask('licensepermitsrenewalemailnotification:daily')->daily();
        $tenantTask('send-mail:audit-delay')->daily();
        $tenantTask('send-mail:remind-evaluate-vendor')->daily();
        $tenantTask('change-risk-status:awaiting-periodic-review')->daily();
        $tenantTask('riskreminderforaddmitigationaction:daily')->daily();
        $tenantTask('racmapprovalrequestnotification:daily')->daily();
        $tenantTask('racmreviewrequestnotification:daily')->daily();
        $tenantTask('externalcalibrationstatusupdatenotification:daily')->dailyAt('02:00');
        $tenantTask('internalcalibrationstatusupdatenotification:daily')->dailyAt('02:30');
        $tenantTask('opportunityissuedueremainder:daily')->dailyAt('01:30');
        $tenantTask('compliance-review-evaluation-status-update:daily')->dailyAt('00:40');
        $tenantTask('send-corrective-escalation-alert-email:daily')->dailyAt('01:00');
        $tenantTask('send-customer-complaint-escalation-alert-email:daily')->dailyAt('00:30');
        $tenantTask('generate-profile-picture-when-null:daily')->dailyAt('01:15');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Commands/Saas');

        require base_path('routes/console.php');
    }
}
