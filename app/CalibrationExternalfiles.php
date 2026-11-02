<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalibrationExternalfiles extends Model
{
    protected $table = "calibration_externalfiles";
    protected $fillable = [
       'Instrument_no',
       'Type',
       'Filename',
       'instrument_id',
    ];
}
