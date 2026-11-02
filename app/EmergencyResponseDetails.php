<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyResponseDetails extends Model
{
    protected $table = 'emergency_response_details';
    protected $fillable = [
                            'department_id','location_id',
                            'emergency_situation_name','emergency_situation_desp',
                            'frequency','drill_team_id','drill_date_last',
                            'drill_date_next','added_by','duedate','status',
                          ];

  public function departmentdetails()
  {
      return $this->belongsTo('App\Departments','department_id');
  }
  public function locationdetails()
  {
      return $this->belongsTo('App\Location','location_id');
  }
  public function addedby()
  {
      return $this->belongsTo('App\user','added_by');
  }
}
