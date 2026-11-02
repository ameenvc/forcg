<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeResponseDrillTeamDetails extends Model
{
    protected $table = 'emergency_response_drill_team_details';
    protected $fillable = [
                           'emergency_response_details_id','drill_team_id',
                          ];
}
