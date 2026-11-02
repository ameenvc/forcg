<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCustomFieldFillable;

class CalibrationExternal extends Model
{
    use HasCustomFieldFillable;
    protected $table = "calibration_external";
    protected $fillable = [
       'Instrument_no',
       'Duedate',
       'Dateofcal',
       'Status',
       'Report_no',
       'instrument_id'
    ];

    public function reportdetails()
    {
        return $this->belongsTo('App\Calibration_instrument', 'instrument_id', 'id');
    }
}