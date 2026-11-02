<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Issuereviewer;
use App\Corrective;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\User;
use App\Mail\EscalationAlert;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendCorrectiveEscalationAlertEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-corrective-escalation-alert-email:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Escalation emails when Correction & Corrective actions are not initiated within the defined target date';

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
        if(!isModuleEnabled('correctiveaction')){
            $this->info('Corrective Action module is not enabled.');
            return;
        }
        $today = date('Y-m-d');
        $appUrl = BaseController::addSlashToEndOfURL();
        $issue_reviewers = getNonDeletedUsersEmail(Issuereviewer::pluck('email')->toArray());
        $users = User::where('approved',true)->where('is_email_verified',true)->get();

        //Fetch Escalation Alert email configuration
        $escalation_email = EmailNotification::where('module', 'correctiveaction')
                        ->where('item', 'Escalation Alert: Overdue Action')
                        ->where('is_blocked', false)
                        ->first();
        if(!$escalation_email){
            $this->info('Escalation Alert email configuration not found or is blocked.');
            return;
        }
        $userdatas = EmailNotificationsUserDetails::where('notification_id', $escalation_email->id)->get();

        // Fetch overdue corrective actions and send escalation alert emails
        $datas = Corrective::where('correction_target_date', '<', $today)
            ->whereNotNull('correction_responsibility')
            ->whereNotNull('correction_target_date')
            ->whereIn('processstage', [
                'Awaiting actions', 'RC Updated- Correction Awaiting','Correction Proposed- RC Awaiting'
            ])->select([
                'id','issueid','title','correction_target_date','processstage','responsibility',
                'correction_department','correction_responsibility','notification'
            ])->with(['escalation','correctionDept'])->get();
        if($datas->isEmpty()){
            $this->info('No overdue corrective actions found for escalation.');
            Log::info('No overdue corrective actions found for escalation.');
            return;
        }
        $frequencyDays = [
            'daily'   => 1,
            'weekly'  => 7,
            'monthly' => 30,
            'yearly'  => 365,
        ];
        $frequency = $escalation_email->frequently ?? 'monthly';
        // Prepare email data and send escalation alert emails
        foreach ($datas as $data) {
            $esc = $data->escalation;
            if (!$esc) continue;
            $targetDate = Carbon::parse($data->correction_target_date);
            if (now()->lte($targetDate)) continue;
            $daysPassed = Carbon::parse($esc->last_escalation_sent_at)
                        ->startOfDay()
                        ->diffInDays(now()->startOfDay());
            // First escalation
            if (is_null($esc->first_escalation_sent_at)) {
                if (now()->gt($targetDate)) {
                    Log::info('First escalation alert email after 1 day: ' . $data->issueid);
                    $this->sendEscalationAlertEmail($data, $escalation_email,$users,$issue_reviewers,$userdatas,$appUrl);
                    $esc->update([
                        'first_escalation_sent_at' => now(),
                        'last_escalation_sent_at' => now(),
                    ]);
                }
            }
            // Frequency based escalation
            elseif(
                isset($frequencyDays[$frequency]) &&
                $daysPassed >= $frequencyDays[$frequency]
            ) {
                Log::info('Escalation alert email based on frequency: ' . $data->issueid);
                $this->sendEscalationAlertEmail($data,$escalation_email,$users,$issue_reviewers,$userdatas,$appUrl);
                $esc->update([
                    'last_escalation_sent_at' => now(),
                ]);
            }
        }
    }
    private function sendEscalationAlertEmail($data, $escalation_email,$users,$issue_reviewers,$userdatas,$appUrl)
    {
        try {
            // Logic to fetch overdue corrective actions and send escalation alert emails
            $emailData = [
                'item' => $escalation_email->item,
                'email_content' => $escalation_email->email_content,
                'issueid' => $data->issueid,
                'title' => $data->title,
                'targetdate' => date('d-m-Y',strtotime($data->correction_target_date)),
                'status' => $data->processstage,
                'person' => $users->whereIn('email', explode(',',$data->correction_responsibility))->pluck('name')->implode(', '),
                'department' => $data->correctionDept ? $data->correctionDept->departmentname : '',
            ];
            if($data->correction_responsibility){
                $responsibilities = getNonDeletedUsersEmail(explode(',',$data->correction_responsibility));
                if(count($responsibilities) > 0){
                    if($data->processstage == 'Correction Proposed- RC Awaiting'){
                        $emailData['url'] = $appUrl . 'user/corrective/editrespondedissue?val='.$data->id;
                        $emailData['btn_text'] = 'Edit Propose Action';
                    }else{
                        $emailData['url'] = $appUrl . 'user/corrective/proposeaction?val='.$data->id;
                        $emailData['btn_text'] = 'Propose Action';
                    }
                    Mail::to($responsibilities)->send(new EscalationAlert($emailData));
                }
            }
            if($data->responsibility){
                $responsibilities = getNonDeletedUsersEmail(explode(',',$data->responsibility));
                if(count($responsibilities) > 0){
                    $emailData['url'] = $appUrl . 'user/corrective/editrespondedissue?val='.$data->id;
                    $emailData['btn_text'] = 'Edit Propose Action';
                    if($data->processstage != 'Awaiting actions'){
                        Mail::to($responsibilities)->send(new EscalationAlert($emailData));
                    }
                }
            }
            if(count($issue_reviewers) > 0){
                $emailData['url'] = $appUrl . 'user/corrective/corrissues/'.$data->id.'/detail';
                $emailData['btn_text'] = 'View Issue Details';
                Mail::to($issue_reviewers)->send(new EscalationAlert($emailData));
            }
            if($data->notification){
                $notificationUsers = getNonDeletedUsersEmail(explode(',',$data->notification));
                if(count($notificationUsers) > 0){
                    Log::info("Notification: ".json_encode($emailData,JSON_PRETTY_PRINT));
                    $emailData['url'] = $appUrl . 'user/corrective/corrissues/'.$data->id.'/detail';
                    $emailData['btn_text'] = 'View Issue Details';
                    Mail::to($notificationUsers)->send(new EscalationAlert($emailData));
                }
            }
            if (count($userdatas) > 0) {
                $adminaddedusers = $userdatas->pluck('user_id')->toArray();
                $userdatas = $users->whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas)>0) {
                    $emailData['url'] = $appUrl . 'user/corrective/corrissues/'.$data->id.'/detail';
                    $emailData['btn_text'] = 'View Issue Details';
                    Mail::to($userdatas)->send(new EscalationAlert($emailData));
                }
            }
        } catch (\Exception $e) {
            $this->error('Error sending escalation alert email: ' . $e->getMessage());
            Log::info('Error sending escalation alert email: ' . $e->getMessage());
        }
    }
}
