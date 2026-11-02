<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeIllnessTreatmentDetails extends Model
{
    protected $table = 'employeeillness_treatmentdetails';
    protected $fillable = [
                            'treatment_id',
                            'illness_record_id'
                          ];

}
