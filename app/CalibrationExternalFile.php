<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed Instrument_no
 * @property mixed Type
 * @property mixed Filename
 * @property mixed instrument_id
 */
class CalibrationExternalFile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calibration_externalfiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'Instrument_no', 'Type', 'Filename', 'instrument_id'
    ];
}
