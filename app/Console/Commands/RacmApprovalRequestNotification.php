<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RiskRacm;
use App\RacmReviewerDetail;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\User;
use App\Mail\RacmApprovalRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;

class RacmApprovalRequestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'racmapprovalrequestnotification:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email notification to approver to approve RACMs records.';

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
        if(!isModuleEnabled('riskmanagement')){
            $this->info('Risk Management module is not enabled.');
            return;
        }
        $approvers = RiskRacm::where('status', 'Awaiting Approval')->distinct()->pluck('approver_id')->toArray();
        $appUrl = BaseController::addSlashToEndOfURL();
        foreach($approvers as $approver){ 
            $racms = RiskRacm::where('approver_id',$approver)->where('status','Awaiting Approval')->pluck('id')->toArray();
            $racms_ids = implode(',',$racms);
            $user = User::findOrFail($approver);
            $approve_emails = EmailNotification::where('module', 'riskmanagement')
                ->where('item', 'Approve RACM Records')
                ->where('is_blocked', false)
                ->first();
            if($approve_emails){
                $data = [
                    'item' => $approve_emails->item,
                    'email_content' => $approve_emails->email_content,
                    'app_url' => $appUrl,
                    'racmids' => $racms_ids,
                    'approver' => $user->name
                ];
                if($user->email){
                    try {
                        Mail::to($user->email)->send(new RacmApprovalRequest($data));
                    } catch (\Throwable $th) {
                        $this->error("Failed to send email to: {$user->email}. Error: {$th->getMessage()}");
                    }
                }
                $approve_userdatas = EmailNotificationsUserDetails::where('notification_id', $approve_emails->id)->get();
                if (count($approve_userdatas) > 0) {
                    $adminaddedusers = $approve_userdatas->pluck('user_id')->toArray();
                    $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    if (count($userdatas)>0) {
                        try {
                            Mail::to($userdatas)->send(new RacmApprovalRequest($data));
                        } catch (\Throwable $th) {
                            $this->error("Failed to send email. Error: {$th->getMessage()}");
                        }
                        
                    }
                }
            }
        }
        $this->info('Notification for approval of racms has been sent successfully');
    }
}
