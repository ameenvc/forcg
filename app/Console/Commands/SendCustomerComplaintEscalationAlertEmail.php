<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BaseController;
use App\EmailNotification;
use App\Complaint;
use App\Complaintreviewer;
use App\User;
use App\Models\ComplaintEscalation;
use App\EmailNotificationsUserDetails;
use App\Mail\ComplaintEscalationAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCustomerComplaintEscalationAlertEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-customer-complaint-escalation-alert-email:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send escalation emails when Correction & Corrective Actions are not initiated within the defined target date';

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
        if(!isModuleEnabled('complaintmanagement')){
            info('Customer Complaint module is not enabled.');
            return;
        }
        $today = date('Y-m-d');
        $appUrl = BaseController::addSlashToEndOfURL();

        // check escalation email enabled or exists
        $escalation_email = EmailNotification::where('module', 'complaintmanagement')
            ->where('item', 'Escalation Alert: Overdue Action')
            ->where('is_blocked', false)
            ->first();

        if (!$escalation_email) {
            info('no escallation email enabled or exist');
            return;
        }

        $datas = Complaint::where('tardate', '<', $today)
                    ->whereNotNull('tardate')
                    ->whereIn('status', [
                        'Approved', 'Correction Proposed- RC Awaiting','RC Updated- Correction Awaiting'
                    ])
                    ->with('department', 'complaintEscalation')->orderBy('id', 'desc')->get();

        if($datas->isEmpty()){
            $this->info('No overdue customer complaint found for escalation.');
            return;
        }

        // complaint reviewers
        $complaint_reviewers = getNonDeletedUsersEmail(Complaintreviewer::pluck('email')->toArray());

        // additional users from admin email notification setting (users added corresponding to the mail notification)
        $escalationEmailNotificationUsersEmail = EmailNotificationsUserDetails::with('user')->where('notification_id', $escalation_email->id)->get()->pluck('user.email')->filter()->toArray();
        $escalationEmailNotificationUsersEmail = getNonDeletedUsersEmail($escalationEmailNotificationUsersEmail);

        $frequency = $this->getFrequency($escalation_email->frequently);
        foreach ($datas as $key => $data) {
            $complaintEscalation = $data->complaintEscalation;
            if (!$complaintEscalation) {
                info('first escalation email: '.$data->trackno);
                $this->sendEscalationAlertEmail($escalation_email, $data, $complaint_reviewers, $escalationEmailNotificationUsersEmail, $appUrl);
                ComplaintEscalation::create([
                    'complaint_id' => $data->id,
                    'first_escalation_sent_at' => now(),
                    'last_escalation_sent_at' => now(),
                ]);
            } else if($complaintEscalation->last_escalation_sent_at && now()->addMinutes(30)->diffInDays($complaintEscalation->last_escalation_sent_at) >= $frequency) {
                info('escalation email after frequency: '.$data->trackno);
                $this->sendEscalationAlertEmail($escalation_email, $data, $complaint_reviewers, $escalationEmailNotificationUsersEmail, $appUrl);
                $complaintEscalation->update([
                    'last_escalation_sent_at' => now(),
                ]);
            }
        }
    }

    private function sendEscalationAlertEmail($escalation_email, $data, $complaint_reviewers, $escalationEmailNotificationUsersEmail, $appUrl) {
        try {
            info('sending mail');
            $responsiblePersonsEmail = getNonDeletedUsersEmail(explode(',',$data->responsibility));
            $responsiblePersons = User::whereIn('email', $responsiblePersonsEmail)->get()->pluck('name')->filter()->implode(', ');
            $sendNotificationsTo = getNonDeletedUsersEmail(explode(',',$data->sendnotif));
            info($sendNotificationsTo);
            $emailData = [
                'id' => $data->id,
                'complaint_id' => $data->trackno,
                'title' => $data->title,
                'target_date' => $data->tardate,
                'status' => $data->status,
                'responsible_person' => $responsiblePersons,
                'responsible_department' => $data->department ? $data->department->departmentname : '' ,
                'email_item' => $escalation_email->item,
                'email_description' => $escalation_email->description,
                'email_content' => $escalation_email->email_content
            ];
            if ($responsiblePersonsEmail) {
                if ($emailData['status'] == 'Approved') {
                    $emailData['url'] = $appUrl . "user/complaint/proposeaction?val={$emailData['id']}";
                    $emailData['btn_text'] = 'Propose Action';
                } else if (in_array($emailData['status'], ['Correction Proposed- RC Awaiting', 'RC Updated- Correction Awaiting', 'Action Rejected'])) {
                    $emailData['url'] = $appUrl . "user/complaint/editproposeaction?val={$emailData['id']}";
                    $emailData['btn_text'] = 'Edit Propose Action';
                }
                Mail::to($responsiblePersonsEmail)->send(new ComplaintEscalationAlert($emailData));
            }
            if ($sendNotificationsTo) {
                $emailData['url'] = $appUrl . "user/complaint/{$emailData['id']}/detail";
                $emailData['btn_text'] = 'View Complaint Details';
                Mail::to($sendNotificationsTo)->send(new ComplaintEscalationAlert($emailData));
            }
            if ($complaint_reviewers) {
                $emailData['url'] = $appUrl . "user/complaint/{$emailData['id']}/detail";
                $emailData['btn_text'] = 'View Complaint Details';
                Mail::to($complaint_reviewers)->send(new ComplaintEscalationAlert($emailData));
            }
            if ($escalationEmailNotificationUsersEmail) {
                $emailData['url'] = $appUrl . "user/complaint/{$emailData['id']}/detail";
                $emailData['btn_text'] = 'View Complaint Details';
                Mail::to($escalationEmailNotificationUsersEmail)->send(new ComplaintEscalationAlert($emailData));
            }
            info('sent mail');
        } catch (\Exception $e) {
            $this->error('Error sending escalation alert email: ' . $e->getMessage());
            Log::info('Error sending escalation alert email: ' . $e->getMessage());
        }
    }

    private function getFrequency($frequently) {

        switch ($frequently) {
            case EmailNotification::DAILY_STATUS:
                $days = 1;
                break;
            case EmailNotification::WEEKLY_STATUS:
                $days = 7;
                break;
            case EmailNotification::MONTHLY_STATUS:
                $days = 30;
                break;
            case EmailNotification::YEARLY_STATUS:
                $days = 365;
                break;
            default:
                $days = 30;
                break;
        }
        return $days;
    }
}
