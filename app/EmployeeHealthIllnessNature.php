<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeHealthIllnessNature extends Model
{
     protected $table = 'employee_health_illness_natures';
     protected $fillable = ['name','illness_type'];
}
