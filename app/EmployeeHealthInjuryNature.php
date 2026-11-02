<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeHealthInjuryNature extends Model
{
     protected $table = 'employee_health_injury_natures';
     protected $fillable = ['name','injury_type'];

}
