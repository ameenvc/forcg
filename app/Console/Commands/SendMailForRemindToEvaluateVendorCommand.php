<?php

namespace App\Console\Commands;

use App\EmailNotification;
use App\EmailNotificationsUserDetails;
use App\Http\Controllers\BaseController;
use App\Mail\RequestEvaluation;
use App\User;
use App\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailForRemindToEvaluateVendorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-mail:remind-evaluate-vendor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email notification to remind to evaluate the vendor';

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
        $this->info('● PROCESSING.....');
        if(isModuleEnabled('vendormanagement')){
        
            $currentDate = date('Y-m-d');
            Log::channel('command')->info('The process is start(' . $currentDate . ').....');
            $vendors = Vendor::whereNotNull('end_user_id')
                ->whereNotNull('system_compliance_user_id')
                ->whereNotNull('supply_chain_representative_user_id')
                ->doesntHave('histories')
                ->get();

            Log::channel('command')->info('Number of vendor that has not yet evaluated: ' . count($vendors));
            $emailNotification = EmailNotification::where('module', 'vendormanagement')
                ->where('item', 'Request evaluation vendor')
                ->where('is_blocked', false)
                ->first();
            $emailNotificationUsers = EmailNotificationsUserDetails::where('notification_id', $emailNotification->id)->get();
            $recipientIds = [];
            $additionalIds = [];
            if (count($emailNotificationUsers) > 0) {
                $additionalIds = $emailNotificationUsers->pluck('user_id')->toArray();
            }

            $recipientIds = array_merge($recipientIds, $additionalIds);
            $recipientIds = array_merge($recipientIds, $vendors->pluck('end_user_id')->toArray());
            $recipientIds = array_merge($recipientIds, $vendors->pluck('system_compliance_user_id')->toArray());
            $recipientIds = array_merge($recipientIds, $vendors->pluck('supply_chain_representative_user_id')->toArray());
            $recipients = User::whereIn('id', $recipientIds)->get();
            $appUrl = BaseController::addSlashToEndOfURL();

            foreach ($vendors as $vendor) {
                Log::channel('command')->info('Vendor ID: ' . $vendor->id);
                $emailData = [
                    'app_url' => $appUrl,
                    'email_content' => htmlspecialchars($emailNotification->email_content),
                    'vendor_id' => $vendor->id,
                    'vendor_name' => $vendor->vendor_name,
                ];

                $endUser = $recipients->where('id', $vendor->end_user_id)->first();
                $emailData['name'] = $endUser->name;
                Mail::to($endUser)->send(new RequestEvaluation($emailData));

                $systemComplianceUser = $recipients->where('id', $vendor->system_compliance_user_id)->first();
                $emailData['name'] = $systemComplianceUser->name;
                Mail::to($systemComplianceUser)->send(new RequestEvaluation($emailData));

                $supplyChainRepresentativeUser = $recipients->where('id', $vendor->supply_chain_representative_user_id)->first();
                $emailData['name'] = $supplyChainRepresentativeUser->name;
                Mail::to($supplyChainRepresentativeUser)->send(new RequestEvaluation($emailData));

                if (count($additionalIds)) {
                    foreach ($additionalIds as $additionalId) {
                        $additionalUser = $recipients->where('id', $additionalId)->first();
                        $emailData['name'] = $additionalUser->name;
                        Mail::to($additionalUser)->send(new RequestEvaluation($emailData));
                    }
                }

                $this->info('● Vendor ID [' . $vendor->id . ']: DONE');
                Log::channel('command')->info('● [' . $vendor->id . ']: DONE');
            }
        }else{
            $this->info('● The vendor management module is disabled.');
            Log::channel('command')->info('The vendor management module is disabled.');
        }
        $this->info('● DONE');
        Log::channel('command')->info('The process did finish.');

        return 0;
    }
}
