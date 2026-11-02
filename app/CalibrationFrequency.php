<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed frequency
 */
class CalibrationFrequency extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calibration_frequency';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'frequency',
    ];
}
