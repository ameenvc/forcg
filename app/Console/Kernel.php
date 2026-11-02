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
        //$schedule->command('calibrationstatusupdate:daily')->dailyAt('01:00');
        $schedule->command('documentreadrequestemailnotification:daily')->daily();
        $schedule->command('licensepermitsrenewalemailnotification:daily')->daily();
        $schedule->command('send-mail:audit-delay')->daily();
        $schedule->command('send-mail:remind-evaluate-vendor')->daily();
        $schedule->command('change-risk-status:awaiting-periodic-review')->daily();
        $schedule->command('riskreminderforaddmitigationaction:daily')->daily();
        $schedule->command('racmapprovalrequestnotification:daily')->daily();
        $schedule->command('racmreviewrequestnotification:daily')->daily();
        $schedule->command('externalcalibrationstatusupdatenotification:daily')->dailyAt('02:00');
        $schedule->command('internalcalibrationstatusupdatenotification:daily')->dailyAt('02:30');
        $schedule->command('opportunityissuedueremainder:daily')->dailyAt('01:30');
        $schedule->command('compliance-review-evaluation-status-update:daily')->dailyAt('00:40');
        $schedule->command('send-corrective-escalation-alert-email:daily')->dailyAt('01:00');
        $schedule->command('send-customer-complaint-escalation-alert-email:daily')->dailyAt('00:30');
        $schedule->command('generate-profile-picture-when-null:daily')->dailyAt('01:15');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
