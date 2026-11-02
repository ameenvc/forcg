<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CalibrationController;
use App\Calibration_instrument;


class CalibrationStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calibrationstatusupdate:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calibration Status gets updated';

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
        $today_dt = date('Y-m-d');
        $inst = Calibration_instrument::where('Status','!=','Obsolete')->get();
        foreach($inst as $value)
        {
             $dudate = $value->Duedate;
             $neartoexpiry=date('Y-m-d',strtotime($dudate. ' - 30 days'));

             if($value->Duedate<$today_dt || $value->Status=="Expired")
             {
                $tasks_controller = new CalibrationController;
                $tasks_controller->statusExpired($value->Duedate);
             }
             elseif($neartoexpiry<=$today_dt || $value->Status=="Near to expiry")
             {
                $tasks_controller = new CalibrationController;
                $tasks_controller->statusNeartoexpiry($value->Duedate);
             }

             elseif($value->Duedate>$today_dt || $value->Status=="Calibrated")
             {
                $tasks_controller = new CalibrationController;
                $tasks_controller->statusCalibrated($value->Duedate);
             }

        }
        $this->info('Calibration Status Updated Successfully');
    }
}
