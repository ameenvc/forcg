<?php

namespace App\Helpers\Opportunity;

use App\Mail\NewOpportunityAdded;
use App\Departments;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Opportunity;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class NewOpportunityAddedEmailHelper
{
    public static function sendEmail($id)
    {
        $data = Opportunity::where('id', $id)->first();

        $mails = EmailNotification::where('module', 'opportunity')
            ->where('item', 'New Opportunity Added')
            ->where('is_blocked', false)
            ->first();

        $opparea = Departments::where('id', $data->department)->firstOrFail();
        if($opparea) {
            $opparea = $opparea->departmentname;
        }

        if($mails){
            $mailData = array(
                'dep_id' => $data->dep_id,
                'oppname' => $data->oppname,
                'item' => $mails->item,
                'email_content' => $mails->email_content,
                'department' => $opparea,
                'oppdes' => $data->oppdes,
                'initial_review_url' => config('app.url').'/user/opportunity/update/'.$id,
                'enable_button' => 1
            );
            //Send email to reviewers
            $opportunityApprover = User::find($data->opportunityapprover_id)->email;
            if(!empty($opportunityApprover)){
                try {
                    Mail::to($opportunityApprover)->send(new NewOpportunityAdded($mailData));
                } catch (\Exception $e) {
                    Log::error('Error sending NewOpportunityAdded email to reviewers: ' . $e->getMessage());
                }
            }

            $opportunity_userdatas = EmailNotificationsUserDetails::where('notification_id', $mails->id)->get();
            $userdatas = [];
            if (count($opportunity_userdatas) > 0) {
                $adminaddedusers = $opportunity_userdatas->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if (count($userdatas) > 0) {
                    try {
                        $mailData['enable_button'] = 0;
                        Mail::to($userdatas)->send(new NewOpportunityAdded($mailData));
                    } catch (\Exception $e) {
                        Log::error('Error sending NewOpportunityAdded email to user added by admin in admin panel: ' . $e->getMessage());
                    }
                }
            }
        }else{
            Session::flash('err', 'Mail Sending Blocked for New Opportunity Added.');
        }

    }
}
