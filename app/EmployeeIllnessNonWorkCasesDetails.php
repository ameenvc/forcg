<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeIllnessNonWorkCasesDetails extends Model
{
    protected $table = "employee_illness_non_work_cases_details";
    protected $fillable = ['illness_id','illness_record_id','employee_num','dateofillness'];
}
