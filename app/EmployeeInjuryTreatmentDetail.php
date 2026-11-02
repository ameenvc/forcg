<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeInjuryTreatmentDetail extends Model
{
    protected $fillable = [
                            'treatment_id',
                            'injury_record_id'
                          ];

}
