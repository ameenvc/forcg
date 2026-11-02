<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed Instrument_no
 * @property mixed Instrument_desc
 * @property mixed Instru_Manufacturer
 * @property mixed Department
 * @property mixed Holder
 * @property mixed Capacity
 * @property mixed Type
 * @property mixed Calib_procedure
 * @property mixed Agency
 * @property mixed Frequency
 * @property mixed duedate1
 * @property mixed duedate2
 * @property mixed duedate3
 * @property mixed Status
 * @property mixed duedate4
 * @property mixed Duedate
 * @property mixed dateofcal
 * @property mixed standard_edition
 * @property mixed acceptance_criteria
 * @property mixed extfile
 * @property mixed least_count
 */
class Calibration_instrument extends Model
{
    /**
     * ------------------------------
     * Status constant
     * ------------------------------
     */
    const UNDER_CALIBRATION_STATUS = 'Under Calibration';
    const EXPIRED_STATUS = 'Expired';
    const NEAR_TO_EXPIRED_STATUS = 'Near to expiry';
    const CALIBRATED_STATUS = 'Calibrated';
    const NOT_CALIBRATED_STATUS = 'Not Calibrated';

    /**
     * ------------------------------
     * Type constant
     * ------------------------------
     */
    const INTERNAL_TYPE = 'internal';
    const EXTERNAL_TYPE = 'external';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calibration_instruments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'Instrument_no', 'Instrument_desc', 'Instru_Manufacturer', 'Department',
        'Holder', 'Capacity', 'Type', 'Calib_procedure',
        'Agency', 'Frequency', 'duedate1',
        'duedate2', 'duedate3', 'Status',
        'duedate4', 'Duedate', 'dateofcal', 'standard_edition', 'acceptance_criteria',
        'extfile', 'least_count','date_of_first_use', 'calibration_field_rules'
    ];


    /**
     * Get the department details associated with the instrument.
     */
    public function departmentdetails(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Departments', 'Department', 'id');
    }

    /**
     * Get the calibration external associated with the instrument.
     */
    public function calibrationExternalReportNo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\CalibrationExternal', 'id', 'instrument_id');
    }

    /**
     * Get the calibration internal associated with the instrument.
     */
    public function calibrationInternalReportNo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\CalibrationInternal', 'id', 'instrument_id');
    }

    /**
     * Get the calibration external files associated with the instrument.
     */
    public function calibrationExternalFiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\CalibrationExternalFile', 'instrument_id', 'id');
    }
}
