<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyResponseDrillReportCorrective extends Model
{
    protected $fillable = ['drill_report_id','corrective_id','drill_report_number','count'];
}
