<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalibrationFieldHistory extends Model
{
    protected $fillable = [
        'calibration_field_id',
        'previous_display_label',
        'updated_display_label',
        'updated_by'
    ];

    public function calibrationField()
    {
        return $this->belongsTo('App\Models\CalibrationField');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }
}
