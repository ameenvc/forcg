<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCustomFieldFillable;

class CalibrationInternal extends Model
{
    use HasCustomFieldFillable;
    protected $table = "calibration_internal";
    protected $fillable = [
        'Instrument_no',
        'Acc_error',
        'M_instru',
        'Certificate_no',
        'M_reading',
        'Instrument_reading',
        'Error',
        'Remarks',
        'Report_no',
        'Adj_made',
        'Calibrated_by',
        'Verified_by',
        'Dateofcal',
        'Duedate',
        'Status',
        'instrument_id'
    ];
    protected static function booted()
    {
        static::retrieved(function ($model) {
            $model->loadCustomFields();
        });

        static::creating(function ($model) {
            $model->loadCustomFields();
        });

        static::updating(function ($model) {
            $model->loadCustomFields();
        });
    }
}