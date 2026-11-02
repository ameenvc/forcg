<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Risk;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\User;
use DB;
use App\Mitigation;
use App\Mail\ReminderForAddMitigationAction;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;

class RiskReminderForAddMitigationAction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riskreminderforaddmitigationaction:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email notification to add a mitigation action for a risk, 6 days after it has been added.';

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
        if(isModuleEnabled('riskmanagement')){
            $risks = Risk::whereIn('rank',['High','Moderate'])->get();
            $today = date('Y-m-d');
            if(count($risks)>0){
                foreach($risks as $risk){
                    $checkaction = Mitigation::where('risk_id',$risk->Rid)->first();
                    $mitigation_emails = EmailNotification::where('module', 'riskmanagement')
                        ->where('item', 'Reminder For Add Mitigation Actions')
                        ->where('is_blocked', false)
                        ->first();
                    if(!$checkaction && $mitigation_emails){
                        $created_date = date('Y-m-d',strtotime($risk->created_at."+6 days"));
                        $appUrl = BaseController::addSlashToEndOfURL();
                        if($today >= $created_date){
                            $mitigation_emails->email_content = str_replace('[Risk Name]', $risk->riskname, $mitigation_emails->email_content);
                            $data = [
                                'risk_name' => $risk->riskname,
                                'item' => $mitigation_emails->item,
                                'email_content' => $mitigation_emails->email_content,
                                'rid' => $risk->grp_id,
                                'risklevel' => $risk->rank,
                                'description' => $risk->riskdec,
                                'addeddate' => date('d-m-Y',strtotime($risk->created_at)),
                                'app_url' => $appUrl,
                                'riskid' => $risk->Rid
                            ];
                            if($risk->risk_owner){
                                $owners = explode(',',$risk->risk_owner);
                                foreach($owners as $owner){
                                    if(User::where('email',$owner)->value('name')){
                                        $data['risk_owner'] = User::where('email',$owner)->value('name');
                                        Mail::to($owner)->send(new ReminderForAddMitigationAction($data));
                                    }
                                
                                }
                            }
                            $risk_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $mitigation_emails->id)->get();
                            if (count($risk_respon_userdatas) > 0) {
                                $adminaddedusers = $risk_respon_userdatas->pluck('user_id')->toArray();
                                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                                if (count($userdatas)>0) {
                                    Mail::to($userdatas)->send(new ReminderForAddMitigationAction($data));
                                }
                            }
                        }
                    }
                }
            }
            $this->info('Reminder for add mitigation action sent successfully!');
        }else{
            $this->info('Risk management module is not enabled!');
        }
    }
}