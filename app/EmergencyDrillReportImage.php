<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyDrillReportImage extends Model
{
    protected $table = 'emergency_drill_report_images';
    protected $fillable = [
                           'drill_report_id','drill_images',
                          ];
}
