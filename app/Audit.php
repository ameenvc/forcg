<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = "audit";
    protected $fillable = [
      'count',
      'aud_id',
      'department',
      'division_id',
      'area_id',
      'hod',
      'scope',
      'auditors',
      'email',
      'contact',
      'year',
      'date',
      'time',
      'enddate',
      'focus',
      'budgeted',
      'quarter',
      'status',
      'reason',
      'observations',
      'related_risks',
      'ncr',
      'high',
      'moderate',
      'low',
      'type',
      'ncrstatus',
      'planned',
      'completed',
      'archived',
      'acceptedby',
      'receive_mail_delay_flag'
    ];

    public function divisiondetails()
    {
       return $this->belongsTo('App\Division', 'division_id', 'id');
    }
    public function areadetails()
    {
       return $this->belongsTo('App\Area', 'area_id', 'id');
    }

    public function departmentObj()
    {
        return $this->belongsTo('App\Departments', 'department', 'id');
    }
}
