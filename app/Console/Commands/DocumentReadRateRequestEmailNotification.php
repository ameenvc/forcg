<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BaseController;
use App\GovernanceHubReadRequestDetail;
use App\GovernanceHub;
use App\User;
use App\Mail\DocumentReadRateRequestNotification;
use Illuminate\Support\Facades\Mail;


class DocumentReadRateRequestEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documentreadrequestemailnotification:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Document Read and Rate Request Email Notification send on scheduled intervals';

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
        $today = date('Y-m-d');
        $notifications = GovernanceHubReadRequestDetail::where('review_completed',false)
                                                       ->where('notification_duedate',$today)
                                                       ->get();
        if(count($notifications)>0){
            foreach($notifications as $notification){
                $document_name = GovernanceHub::where('id',$notification->governance_hub_id)->value('document_name');
                $user_exists = User::where('id', $notification->user_id)->first();
                $app_url = BaseController::addSlashToEndOfURL();
                $maildata = [
                    'sender_name' => $this->getuserdetails($notification->read_request_submitted_by),
                    'rate_user'=>$this->getuserdetails($notification->user_id),
                    'document_name' => $document_name ? ucfirst($document_name):'',
                    'app_url' => $app_url,
                    'id' => $notification->governance_hub_id,
                ];
              Mail::to($user_exists->email)->send(new DocumentReadRateRequestNotification($maildata));
              $next_due_date = $this->getreadrequestschedule($today,$notification->notification_interval);
              GovernanceHubReadRequestDetail::where('id',$notification->id)
                                             ->update(['notification_duedate'=>$next_due_date]);
            }
            $this->info('Document Read and Rate Email Notification Send Successfully');  
        }
    }

    protected function getuserdetails($value){
        $user_details = User::where('id',$value)->first();
        $user_name = $user_details?$user_details->name:'';
        return $user_name;    
     }
    protected function getreadrequestschedule($start_date,$interval){
        if($interval == 'Monthly'){
            $due_date = date("Y-m-d",strtotime($start_date . ' + 30 days'));
        }
        else if($interval == 'Weekly'){
            $due_date = date("Y-m-d",strtotime($start_date . ' + 7 days'));
        }
        else if($interval == 'Daily'){
            $due_date = date("Y-m-d",strtotime($start_date . ' + 1 days'));
        }
        else if($interval == 'Every 3 Days'){
            $due_date = date("Y-m-d",strtotime($start_date . ' + 3 days'));
        }
        else if($interval == 'Every 2 Days'){
            $due_date = date("Y-m-d",strtotime($start_date . ' + 2 days'));
        }
        return $due_date;
    }
}
