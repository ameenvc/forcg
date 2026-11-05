<?php
namespace App\Helpers\Risk;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Departments;
use App\Riskreviewer;
use App\User;
use App\Risk;
use App\Mail\NewRiskAdded;
use Session;

class NewRiskAddedEmailHelper
{
    public static function sendEmail($id) {
        try {
            return self::deliver($id);
        } catch (\Swift_TransportException $exception) {
            // Callers have already saved the risk. Do not turn a notification
            // transport failure into an apparent submission failure or retry it
            // automatically: earlier recipients may already have received mail.
            Log::warning('Risk notification transport failed.', [
                'risk_id' => $id,
                'transport_code' => $exception->getCode(),
            ]);
            return false;
        }
    }

    private static function deliver($id) {
        $data = Risk::where('Rid', $id)->first();
        // starting email notification
        $mails = EmailNotification::where('module','riskmanagement')
                    ->where('item','New Risk Added')
                    ->where('is_blocked',false)
                    ->first();
        if($mails)
        {
            $mailData = array(
                'riskarea' => Departments::where('id', $data->riskarea)->value('departmentname'),
                'riskname' => $data->riskname,
                'riskdec' => $data->riskdec,
                'grp_id' => $data->grp_id,
                'item' => $mails->item,
                'email_content' => $mails->email_content,
                'initial_review_url' => config('app.url').'/user/risks/updaterisk?val='.$id,
                'enable_button' => 1
            );

            // fetching reviewer email id
            $riskreviewer = User::find($data->riskreviewer_id)->email;

            //Sending Mail To Risk Reviewers
            if(!empty($riskreviewer)){
                Mail::to($riskreviewer)->send(new NewRiskAdded($mailData));
            }

            $userdatas1 = EmailNotificationsUserDetails::where('notification_id',$mails->id)->get();
            if(count($userdatas1) > 0)
            {
                $adminaddedusers = $userdatas1->pluck('user_id')->toArray();
                $userdatas = User::whereIn('id', $adminaddedusers)->pluck('email')->toArray();
                if(!empty($userdatas)){
                    $mailData['enable_button'] = 0;
                    Mail::to($userdatas)->send(new NewRiskAdded($mailData));
                }
            }
            return true;
        }
        else
        {
            return false;
        }

    }
}
