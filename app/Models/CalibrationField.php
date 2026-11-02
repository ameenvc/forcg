<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalibrationField extends Model
{
    protected $fillable = [
        'enable_field',
        'field_name',
        'field_type',
        'default_display_label',
        'field_display_label',
        'field_is_mandatory',
        'updated_by',
        'order'
    ];

    public function calibrationFieldHistories()
    {
        return $this->hasMany('App\Models\CalibrationFieldHistory');
    }

    public function calibrationFieldMetas()
    {
        return $this->hasMany('App\Models\CalibrationFieldMeta');
    }
}
