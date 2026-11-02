<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeemedicalcheckuprecord extends Model
{
    protected $fillable=[
                        'emp_number','date_of_medical','tobacco',
                        'bp_diastolic','bp_systolic','bs_level',
                        'remarks','health_record_id','employee_id'
                        ];

}
