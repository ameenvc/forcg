<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeIllnessPandemicContact extends Model
{
    protected $table = 'employee_illness_pandemic_contacts';
    protected $fillables = ['illness_record_id','contact_emp_name','contact_emp_number'];
}
