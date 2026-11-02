<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeInjuryWorkRelatedCases extends Model
{
    protected $fillable = ['injury_id','injury_record_id','employee_num','dateofinjury'];
}
