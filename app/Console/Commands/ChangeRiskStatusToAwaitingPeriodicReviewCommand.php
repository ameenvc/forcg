<?php

namespace App\Console\Commands;

use App\Risk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChangeRiskStatusToAwaitingPeriodicReviewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change-risk-status:awaiting-periodic-review {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command change the risk status from Active Risk to Awaiting Periodic Review';

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
        $currentDate = date('Y-m-d');
        $updated_at = now();
        Log::channel('command')->info('[change-risk-status:awaiting-periodic-review] The process is start(' . $currentDate . ').....');
        if ($this->option('date')) {
            $comparingDate = $this->option('date');
        } else {
            $comparingDate = date('Y-m-d', strtotime('-1 year'));
        }

        $riskWithoutPeriodicReviewDate = Risk::select('Rid', 'review_date', 'periodic_review_date','updated_at')
            ->where('action_status', 'Active Risk')
            ->where(DB::raw('DATE(DATE_SUB(review_date, INTERVAL 1 MONTH))'), '=', $comparingDate)
            ->whereNull('periodic_review_date')
            ->get();

        $riskWithPeriodicReviewDate = Risk::select('Rid', 'review_date', 'periodic_review_date','updated_at')
            ->where('action_status', 'Active Risk')
            ->whereNotNull('periodic_review_date')
            ->where(DB::raw('DATE(DATE_SUB(periodic_review_date, INTERVAL 1 MONTH))'), '=', $comparingDate)
            ->get();

        foreach ($riskWithoutPeriodicReviewDate as $risk) {
            $risk->where('Rid',$risk->Rid)->update(['action_status' => 'Awaiting Periodic Review','updated_at'=>$updated_at]);
            Log::channel('command')->info('Risk ID: ' . $risk->Rid);
            $this->info('● [' . $risk->Rid . ']: DONE');
            Log::channel('command')->info('● [' . $risk->Rid . ']: DONE');
        }
        foreach ($riskWithPeriodicReviewDate as $risk) {
            $risk->where('Rid',$risk->Rid)->update(['action_status' => 'Awaiting Periodic Review','updated_at'=>$updated_at]);
            Log::channel('command')->info('Risk ID: ' . $risk->Rid);
            $this->info('● [' . $risk->Rid . ']: DONE');
            Log::channel('command')->info('● [' . $risk->Rid . ']: DONE');
        }

        $this->info('● DONE');
        Log::channel('command')->info('[change-risk-status:awaiting-periodic-review] The process did finish.');

        return 0;
    }
}
