<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Opportunity;
use App\Departments;
use App\Mail\OpportunityOnDueReminder;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Closurereviewer;
use App\User;
use Illuminate\Support\Facades\Mail;

class OpportunityIssueDueEmailRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opportunityissuedueremainder:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Opportunity Issue Due Reminder Emails are sent at scheduled intervals.';

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
        $data = Opportunity::whereIn('status', ['Review Pending', 'Approved', 'Completed'])
            ->whereIn('impact', ['High', 'Low', 'Moderate'])
            ->orderby('id', 'desc')
            ->get();
        $today = date("Y-m-d");
        $reviewer = Closurereviewer::pluck('email')->toArray();
        $reviewer = getNonDeletedUsersEmail($reviewer);
        $departments = Departments::all();
        $departmentNames = $departments->pluck('departmentname','id')->toArray();
        $hods = $departments->pluck('hod','id')->toArray();
        //mail before reaching the target date
        if(count($data)>0){
            foreach ($data as $dat) { 
                $opportunity_emails = EmailNotification::where('module', 'opportunity')
                    ->where('item', 'Opportunity On Due Reminder')
                    ->where('is_blocked', false)
                    ->first();
                if($opportunity_emails){           
                    $maildata = array(
                        'dep_id' => $dat->dep_id,
                        'oppname' => $dat->oppname,
                        'item' => $opportunity_emails->item,
                        'email_content' => $opportunity_emails->email_content,
                        'department' => $departmentNames[$dat->department],
                        'status' => $dat->status,
                        'tardate' => $dat->tardate ? date("d/m/Y", strtotime($dat->tardate)) : '',
                    );
                    $opportunity_userdatas = EmailNotificationsUserDetails::where('notification_id', $opportunity_emails->id)->get();
                    $userdatas = [];
                    if (count($opportunity_userdatas) > 0) {
                        $adminaddedusers = $opportunity_userdatas->pluck('user_id')->toArray();
                        $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                    }
                    // Check if target date is in the future and not disapproved
                    if ($dat->tardate > $today) {
                        // 30 days before target date
                        if ($today == date("Y-m-d", strtotime($dat->tardate . ' -30 days')) && $dat->monthly == 0) {
                            $this->sendMail($maildata,$hods[$dat->department],[],$userdatas);
                            Opportunity::where('id', $dat->id)->update(['monthly' => 1]);
                        }
                        // 2 days before target date
                        if ($today == date("Y-m-d", strtotime($dat->tardate . ' -2 days')) && $dat->twodays == 0) {
                            $this->sendMail($maildata,$hods[$dat->department],[],$userdatas);
                            Opportunity::where('id', $dat->id)->update(['twodays' => 1]);
                        }
                    }
                    // Past due and not disapproved
                    if ($today > $dat->tardate && $dat->due == 0) {
                        $this->sendMail($maildata,$hods[$dat->department],$reviewer,$userdatas);
                        Opportunity::where('id', $dat->id)->update(['due' => 1]);
                    }
                }
            }
        }
        $this->info('Opportunity Issue Due Reminder Emails sent successfully!');
    }
    private function sendMail($emaildata,$hod,$reviewersArray,$userdatas){
        if(isModuleEnabled('opportunity')){
            // try {
                //sending mail for hod persons
                $hod = getNonDeletedUsersEmail($hod);
                if (count($hod) > 0) {
                    Mail::to($hod)->send(new OpportunityOnDueReminder($emaildata));
                }
                // sending mail for reviewer
                $reviewersArray = getNonDeletedUsersEmail($reviewersArray);
                if (count($reviewersArray)>0) {
                    Mail::to($reviewersArray)->send(new OpportunityOnDueReminder($emaildata));
                }
                //sending users added for email notifications in admin panel
                if (count($userdatas) > 0) {
                    Mail::to($userdatas)->send(new OpportunityOnDueReminder($emaildata));
                }
            // } catch (\Exception $e) {
            //     Log::error('Error sending Opportunity Issue Due Reminder Emails: ' . $e->getMessage());
            // }
        }
    }
}
