<?php

namespace App\Helpers\Opportunity;
use App\Opportunity;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Departments;
use App\User;
use App\Mail\OpportunityStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OpportunityStatusEmailHelper
{
    public static function sendEmail($id)
    {
        $data = Opportunity::where('id', $id)->first();

        $mails = EmailNotification::where('module', 'opportunity')
            ->where('item', 'Opportunity Status')
            ->where('is_blocked', false)
            ->first();
        if($mails){
            $mailData = array(
                'dep_id' => $data->dep_id,
                'oppname' => $data->oppname,
                'item' => $mails->item,
                'email_content' => $mails->email_content,
                'department' => $data->department ? Departments::where('id',$data->department)->value('departmentname') : '',
                'oppdes' => $data->oppdes,
                'closure_review_url' => config('app.url').'/user/opportunity/closure/'.$id,
                'enable_button' => 1
            );
            //Send email to closure reviewer
            $closurereviewer = User::find($data->closurereviewer_id);
            if ($closurereviewer) {
                try {
                    Mail::to($closurereviewer->email)->send(new OpportunityStatus($mailData));
                } catch (\Exception $e) {
                    Log::error('Error sending OpportunityStatus email to posted person: ' . $e->getMessage());
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
                        Mail::to($userdatas)->send(new OpportunityStatus($mailData));
                    } catch (\Exception $e) {
                        Log::error('Error sending OpportunityStatus email to user added by admin in admin panel: ' . $e->getMessage());
                    }
                }
            }
        }else{
            Session::flash('err', 'Mail Sending Blocked for Opportunity Status.');
        }
    }
}
