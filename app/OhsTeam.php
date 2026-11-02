<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OhsTeam extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'employee_id',
        'ohs_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\EmployeeDetails', 'employee_id', 'id');
    }

    public function ohs()
    {
        return $this->belongsTo('App\Ohs', 'ohs_id', 'id');
    }
}
