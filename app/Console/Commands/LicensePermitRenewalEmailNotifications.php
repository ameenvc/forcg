<?php

namespace App\Console\Commands;
use App\Compliance;
use App\User;
use App\Legalcoordinator;
use App\LicensePermit;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Mail\LicensePermitRenewalNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LicensePermitRenewalEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licensepermitsrenewalemailnotification:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'License/Permits Renewal Request Email Notification send on scheduled intervals';

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
        $legal = Legalcoordinator::pluck('email')->toArray();
        $coordinatorsArray = User::whereIn('email',$legal)->pluck('email')->toArray();
        $data = LicensePermit::where('is_deleted',false)->orderByDesc('created_at')->get(
            [
                'id','comp_id','compliance_auth_id','name','licensenumber','expirydate','frequency',
                'Dailyfreq','Weeklyfreq','EveryTwodaysfreq','EveryFivedaysfreq','responsible'
            ]
        );
        $today = date("Y-m-d");
        if(count($data)>0){
            foreach ($data as $d) {
                $expirydate = $d->expirydate;
                $oneMonth = date('Y-m-d', strtotime('+1 month', strtotime($today)));
                $status = '';
                if($expirydate < $today)
                    $status = 'Expired';
                elseif($expirydate > $oneMonth)
                    $status = 'Active';
                else
                    $status = 'Near to expiry';
                LicensePermit::where('id', $d->id)->update(array('status' => $status));
                $freq = $d->frequency ? explode(',', $d->frequency) :[];
                // Sending Mail(Responsibility Notification) To responsibile HOD
                $license_respon_emails = EmailNotification::where('module', 'compliance')
                    ->where('item', 'License/Permits Renewal Notification')
                    ->where('is_blocked', false)
                    ->first();
                if($license_respon_emails){           
                    $emaildata = array(
                        'licenseid' => $d->compliance_auth_id,
                        'cid' => Compliance::where('Cid',$d->comp_id)->value('auth_id'),
                        'item' => $license_respon_emails->item,
                        'email_content' => $license_respon_emails->email_content,
                        'name' => $d->name,
                        'licensenumber' => $d->licensenumber,
                        'expirydate' => $d->expirydate ? date("d/m/Y", strtotime($d->expirydate)) : '',
                        'status' => $status,
                    );
                    $license_respon_userdatas = EmailNotificationsUserDetails::where('notification_id', $license_respon_emails->id)->get();
                    $dailyFreq = $d->Dailyfreq ? date('Y-m-d',strtotime($d->Dailyfreq)):'';
                    $weeklyFreq = $d->Weeklyfreq ? date('Y-m-d',strtotime($d->Weeklyfreq)):'';
                    $every2DaysFreq = $d->EveryTwodaysfreq ? date('Y-m-d',strtotime($d->EveryTwodaysfreq)):'';
                    $every5DaysFreq = $d->EveryFivedaysfreq ? date('Y-m-d',strtotime($d->EveryFivedaysfreq)):'';
                    $userdatas = [];
                    if (count($license_respon_userdatas) > 0) {
                        $adminaddedusers = $license_respon_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    }
                    if($status === 'Expired' && $dailyFreq < $today){ 
                        LicensePermit::where('id', $d->id)->update(array('Dailyfreq' => $today));
                        $this->sendMail($emaildata,$d,$coordinatorsArray,$userdatas);
                    }elseif($status == 'Near to expiry'){
                        if (in_array('Daily',$freq) && $dailyFreq < $today) {
                            LicensePermit::where('id', $d->id)->update(array('Dailyfreq' => $today));             
                            $this->sendMail($emaildata,$d,$coordinatorsArray,$userdatas);  
                        } elseif (in_array('Weekly',$freq) && $weeklyFreq < $today) {
                            LicensePermit::where('id', $d->id)->update(array('Weeklyfreq' => date("Y-m-d", strtotime($today.'+6 days'))));
                            $this->sendMail($emaildata,$d,$coordinatorsArray,$userdatas);
                        } elseif (in_array('Every 5 Days',$freq) && $every5DaysFreq < $today) {                              
                            LicensePermit::where('id', $d->id)->update(array('EveryFivedaysfreq' => date("Y-m-d", strtotime($today.'+4 days'))));
                            $this->sendMail($emaildata,$d,$coordinatorsArray,$userdatas);
                        }elseif (in_array('Every 2 Days',$freq) && $every2DaysFreq < $today) {                              
                            LicensePermit::where('id', $d->id)->update(array('EveryTwodaysfreq' => date("Y-m-d", strtotime($today.'+1 day'))));
                            $this->sendMail($emaildata,$d,$coordinatorsArray,$userdatas);
                        }
                    }
                }
            }
        }
        $this->info('License/Permit renewal notifications sent successfully!');
    }
    private function sendMail($emaildata,$d,$coordinatorsArray,$userdatas){
        if(isModuleEnabled('compliance')){
            try {
                $res = explode(',', $d->responsible);
                $responsibilities = User::whereIn('id', $res)->pluck('email')->toArray();
                //sending mail for responsible persons
                if (count($responsibilities) > 0) {
                    Mail::to($responsibilities)->send(new LicensePermitRenewalNotification($emaildata));
                }
                // sending mail for legal coordinator
                if (count($coordinatorsArray)) {
                    Mail::to($coordinatorsArray)->send(new LicensePermitRenewalNotification($emaildata));
                }
                //sending users added for email notifications in admin panel
                if (count($userdatas) > 0) {
                    Mail::to($userdatas)->send(new LicensePermitRenewalNotification($emaildata));
                }
            } catch (\Exception $e) {
                Log::error('Error sending license permit renewal notification: ' . $e->getMessage());
            }
        }
    }
}
