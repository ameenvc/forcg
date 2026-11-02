<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RacmReviewerDetail;
use App\RiskRacm;
use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\User;
use App\Mail\RacmReviewRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;

class RacmReviewRequestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'racmreviewrequestnotification:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email notification to reviewers to review RACMs records.';

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
        $reviewers = RacmReviewerDetail::where('reviewed_status',false)->pluck('reviewer_id')->unique()->toArray();
        $review_emails = EmailNotification::where('module', 'riskmanagement')
            ->where('item', 'Review RACM Records')
            ->where('is_blocked', false)
            ->first();
        if($review_emails){
            $appUrl = BaseController::addSlashToEndOfURL();
            foreach ($reviewers as $reviewer) {
                $racmIds = $this->getRacmIds($reviewer);
                $data = [
                    'item' => $review_emails->item,
                    'email_content' => $review_emails->email_content,
                    'app_url' => $appUrl,
                    'racmids' => $racmIds
                ];
                $user = User::findOrFail($reviewer);
                if($user){
                    $data['reviewer'] = $user->name;
                    $data['reviewer_id'] = $user->id;
                    try {
                        Mail::to($user->email)->send(new RacmReviewRequest($data));
                    } catch (\Throwable $th) {
                        $this->error("Failed to send email to: {$user->email}. Error: {$th->getMessage()}");
                    }
                }
                $review_userdatas = EmailNotificationsUserDetails::where('notification_id', $review_emails->id)->get();
                if (count($review_userdatas) > 0) {
                    $adminaddedusers = $review_userdatas->pluck('user_id')->toArray();
                    if (count($adminaddedusers)>0) {
                        foreach ($adminaddedusers as $adminaddeduser) {
                            $userdata = User::where('id', $adminaddeduser)->first();
                            try {
                                $data['reviewer'] = $$userdata->name;
                                $data['reviewer_id'] = $adminaddeduser;
                                Mail::to($userdata->email)->send(new RacmReviewRequest($data));
                            } catch (\Throwable $th) {
                                $this->error("Error while sending email: {$th->getMessage()}");
                            }
                        }
                    }
                }
            }
        }
        $this->info('Notification for review racms has been sent successfully.');
    }
    public function getRacmIds($reviewer_id) : string {
        $racms = RacmReviewerDetail::where('reviewed_status',false)->where('reviewer_id',$reviewer_id)->distinct()->pluck('racm_id')->toArray();
        $result = RiskRacm::whereIn('id', $racms)
                ->whereIn('status', ['Review Requested', 'Reviewed'])
                ->pluck('id')
                ->toArray();
        return implode(',',$result);
    }
}
