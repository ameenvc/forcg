<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Compliance;
use Illuminate\Support\Facades\Log;

class ComplianceReviewEvaluationStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compliance-review-evaluation-status-update:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command change the compliance status based on the frequency to Awaiting Periodic Evaluation and Periodic Evaluation On Due.';

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
        $data = Compliance::where('comp_status','complied')
                ->select(
                    'Cid','comp_status','frequency','review_evaluation_date','created_at','updated_at','auth_id',
                    'issue','version','regulation','authority','owner','freq_review'
                )->get();
        $today = date('Y-m-d');
        Log::channel('command')->info('[compliance-review-evaluation-status-update] The process is start(' . $today. ').....');
        $twoweeksBefore = date('Y-m-d', strtotime($today. ' - 2 weeks'));
        if(count($data)>0){
            foreach($data as $value){ 
                $status = '';
                $fredate = null;
                if($value->review_evaluation_date){
                    $compareDate = date('Y-m-d', strtotime($value->review_evaluation_date));
                }else{
                    $compareDate = date('Y-m-d', strtotime($value->created_at));
                }                                        
                switch($value->frequency){
                    case 'Quarterly' : 
                        $fredate = date('Y-m-d', strtotime($compareDate. ' + 3 months'));
                        break;
                    case 'Half Yearly' :
                        $fredate = date('Y-m-d', strtotime($compareDate. ' + 6 months'));
                        break;
                    case 'Yearly':
                        $fredate = date('Y-m-d', strtotime($compareDate. ' + 12 months'));
                        break;
                }
                if(!$fredate){
                    continue;
                }
                if($fredate >= $twoweeksBefore  && $fredate < $today){
                    $status = 'Awaiting Periodic Evaluation';
                }elseif($fredate > $today){
                    Log::info('No status change required for Cid '.$value->Cid.' and Compliance ID: '.$value->auth_id.' Current Status: '.$value->comp_status);
                    continue;
                }else{
                    $status = 'Periodic Evaluation On Due';
                }
                if($status != ''){
                    try {
                        Compliance::where('Cid', $value->Cid)
                            ->update([
                                'comp_status' => $status,
                                'updated_at' => now()
                            ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to update compliance status for Cid '.$value->Cid.' and Compliance ID: '.$value->auth_id.': '.$e->getMessage());
                        continue;
                    }
                    Log::info('Compliance status changed to '.$status.' for Cid '.$value->Cid.' and Compliance ID: '.$value->auth_id.' from '.$value->comp_status);
                }
            }
        }
        $this->info('● DONE');
        Log::channel('command')->info('[compliance-review-evaluation-status-update] The process is finished.');
    }
}
