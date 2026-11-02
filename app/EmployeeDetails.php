<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends Model
{
    protected $table = "employee_details";
    protected $fillable=['emp_number','emp_name','emp_position','emp_department','joining_date','emp_dob','is_deleted'];
    public function gates(){
        return $this->hasMany('App\UserGate', 'employeeid', 'employees_id');
    }
    public function department(){
        return $this->belongsTo('App\Departments', 'emp_department', 'id');
    }
}
