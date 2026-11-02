<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalibrationFieldMeta extends Model
{
    protected $fillable = [
        'calibration_field_id',
        'meta_key',
        'meta_value'
    ];

    public function calibrationField()
    {
        return $this->belongsTo('App\Models\CalibrationField');
    }
}
