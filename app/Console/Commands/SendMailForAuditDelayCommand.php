<?php

namespace App\Console\Commands;

use App\Audit;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Mail\AuditDelayNotification;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailForAuditDelayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-mail:audit-delay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email notification to auditee & auditors, if scheduled audit is not conducted by the due date';

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
        $this->info('● PROCESSING.....');
        $currentDate = date('Y-m-d');
        Log::channel('command')->info('The process is start(' . $currentDate . ').....');
        if(isModuleEnabled('auditmanagement')){
            $auditDelayList = Audit::with('departmentObj')
                ->whereNotNull('enddate')
                ->where('enddate', '<', $currentDate)
                ->where('status', '!=', 'Completed')
                ->where('receive_mail_delay_flag', false)
                ->get();

            Log::channel('command')->info('Number of delay audit: ' . count($auditDelayList));
            $emailNotification = EmailNotification::where('module', 'auditmanagement')
                ->where('item', 'Audit delay notification')
                ->where('is_blocked', false)
                ->first();
            $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
            $userData = [];
            if (count($emailNotificationUsers) > 0) {
                $emailNotificationUserList = $emailNotificationUsers->pluck('user_id')->toArray();
                $userData = User::whereIn('id', $emailNotificationUserList)->pluck( 'name', 'email')->toArray();
            }

            foreach ($auditDelayList as $audit) {
                Log::channel('command')->info('Audit ID: ' . $audit->aud_id);
                $auditeeList = explode(',', $audit->contact);
                $auditorsList = explode(',', $audit->auditors);
                //$receiveNames = array_merge($auditeeList, $auditorsList);
                $receiveNames = $auditorsList;
                $receiverList = User::whereIn('name', $receiveNames)->get();
                Log::channel('command')->info('Receiver list: '
                    . count($receiverList) ? implode(',', $receiverList->pluck('email')->toArray()) : '');

                $emailData = [
                    'email_content' => htmlspecialchars($emailNotification->email_content),
                    'department' => $audit->departmentObj ? $audit->departmentObj->departmentname : '',
                    'scope' => $audit->scope,
                    'from_date' => date('d-m-Y', strtotime($audit->date)),
                    'to_date' => date('d-m-Y', strtotime($audit->enddate)),
                    'audit_id' => $audit->aud_id
                ];

                foreach ($receiverList as $receiver) {
                    $emailData['name'] = $receiver->name;
                    Mail::to($receiver)
                        ->send(new AuditDelayNotification($emailData));
                }
                if (count($userData) > 0) {
                    Log::channel('command')->info('extend email: ' . implode(',', $userData));
                    foreach ($userData as $receiver => $receiverName) {
                        $emailData['name'] = $receiverName;
                        Mail::to($receiver)->send(new AuditDelayNotification($emailData));
                    }
                }

                $audit->update(['receive_mail_delay_flag' => true]);
                $this->info('● [' . $audit->aud_id . ']: DONE');
                Log::channel('command')->info('● [' . $audit->aud_id . ']: DONE');
            }
        }else{
            $this->info('● The audit management module is disabled.');
            Log::channel('command')->info('The audit management module is disabled.');
        }
        $this->info('● DONE');
        Log::channel('command')->info('The process did finish.');

        return 0;
    }
}
