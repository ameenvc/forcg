<?php

namespace App\Console\Commands;

use App\Calibrationreviewer;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Mail\DailyForNoticeAboutCalibrationStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Calibration_instrument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class InternalCalibrationStatusUpdateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internalcalibrationstatusupdatenotification:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification when internal Calibration Status Update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        Log::info('[internalcalibrationstatusupdatenotification:daily] START');
        $this->info('[internalcalibrationstatusupdatenotification:daily] START');
        if(isModuleEnabled('calibration')){
            $emailNotification = EmailNotification::where('module', 'calibration')
                ->where('item', 'Email Content for daily notification for Near to expiry & Expired instruments of Internal calibration')
                ->where('is_blocked', false)
                ->first();
            $isCanRun = true;
            if ($emailNotification && $emailNotification->frequently == EmailNotification::WEEKLY_STATUS) {
                $isCanRun = Carbon::now()->isMonday();
            }

            if ($isCanRun) {
                $today = date("Y-m-d");
                $nearToExpiryDate = date('Y-m-d',strtotime($today . ' + 30 days'));
                $calibrations = Calibration_instrument::with('departmentdetails')
                    ->whereNotNull('Duedate')
                    ->whereRaw('LOWER(Type) = (?)', [Calibration_instrument::INTERNAL_TYPE])
                    ->where(function ($query) use ($today, $nearToExpiryDate) {
                        $query->where('Duedate', '<', $today)
                            ->orWhere('Duedate', '<=', $nearToExpiryDate);
                    })
                    ->orderBy('Duedate', 'DESC')
                    ->get();
                Log::info('[internalcalibrationstatusupdatenotification:daily] Number of calibration: ' . count($calibrations));
                $this->info('[internalcalibrationstatusupdatenotification:daily] Number of calibration: ' . count($calibrations));
                $calibrationReviewers = Calibrationreviewer::pluck('email')->toArray();
                Log::info('[internalcalibrationstatusupdatenotification:daily] Number of calibration reviewer: ' . count($calibrationReviewers));
                $this->info('[internalcalibrationstatusupdatenotification:daily] Number of calibration reviewer: ' . count($calibrationReviewers));

                if ($emailNotification && count($calibrationReviewers) && count($calibrations)) {
                    try {
                        $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
                        $additionalEmails = count($emailNotificationUsers)
                            ? User::whereIn('id', $emailNotificationUsers->pluck('user_id')->toArray())->pluck('email')->toArray()
                            : [];
                        Log::info('[internalcalibrationstatusupdatenotification:daily] Number of additional receiver: ' . count($additionalEmails));
                        $this->info('[internalcalibrationstatusupdatenotification:daily] Number of additional receiver: ' . count($additionalEmails));
                        $emailData = [];
                        $emailData['email_content'] = htmlspecialchars($emailNotification->email_content);
                        $emailData['calibrations'] = [];
                        foreach ($calibrations as $calibration) {
                            $nearToExpiry = date('Y-m-d',strtotime($calibration->Duedate . ' - 30 days'));
                            if (strtolower($calibration->Type) == Calibration_instrument::INTERNAL_TYPE) {
                                $calibrationData['url'] = route('int', ['val' => $calibration->id]);
                            } else {
                                $calibrationData['url'] = route('calibration.view.status', ['val' => $calibration->id]);
                            }
                            $calibrationData['instrument_no'] = $calibration->Instrument_no;
                            $calibrationData['previous_status'] =
                                $calibration->Duedate < $today
                                    ? Calibration_instrument::EXPIRED_STATUS
                                    : ($nearToExpiry <= $today
                                        ? Calibration_instrument::NEAR_TO_EXPIRED_STATUS
                                        : $calibration->Status);
                            $calibrationData['department'] = $calibration->departmentdetails
                                ? $calibration->departmentdetails->departmentname
                                : '--';
                            $calibrationData['type'] = $calibration->Type;
                            $calibrationData['due_date'] = $calibration->Duedate
                                ? date('d-m-Y', strtotime($calibration->Duedate))
                                : '--';
                            $emailData['calibrations'][] = $calibrationData;
                        }
                        // Ensure emails are present in users table
                        $validReviewersEmails = User::whereIn('email', $calibrationReviewers)->pluck('email')->toArray();
                        $firstCalibrationReviewersEmail = $validReviewersEmails ? array_shift($validReviewersEmails) : null;
                        Mail::to($firstCalibrationReviewersEmail)
                            ->bcc(array_merge($additionalEmails, $validReviewersEmails))
                            ->send(new DailyForNoticeAboutCalibrationStatus($emailData));
                        Log::info('[internalcalibrationstatusupdatenotification:daily] Send successfully');
                        $this->info('[internalcalibrationstatusupdatenotification:daily] Send successfully');
                    } catch (\Exception $exception) {
                        Log::info('[internalcalibrationstatusupdatenotification:daily] Send failure');
                        $this->error('[internalcalibrationstatusupdatenotification:daily] Send failure');
                        Log::error($exception);
                    }
                } else {
                    if (!$emailNotification) {
                        Log::info('[internalcalibrationstatusupdatenotification:daily] Email notification is not found');
                        $this->info('[internalcalibrationstatusupdatenotification:daily] Email notification is not found');
                    }
                    if (!count($calibrationReviewers)) {
                        Log::info('[internalcalibrationstatusupdatenotification:daily] Calibration reviewer is not found');
                        $this->info('[internalcalibrationstatusupdatenotification:daily] Calibration reviewer is not found');
                    }
                }
            }
        }else{
            Log::info('[internalcalibrationstatusupdatenotification:daily] Calibration module is disabled');
            $this->info('[internalcalibrationstatusupdatenotification:daily] Calibration module is disabled');
        }
        $this->info('[internalcalibrationstatusupdatenotification:daily] END');
        Log::info('[internalcalibrationstatusupdatenotification:daily] END');
    }
}
