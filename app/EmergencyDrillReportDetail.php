<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyDrillReportDetail extends Model
{
    protected $table = 'emergency_drill_report_details';
    protected $fillable = [
                           'emergency_details_id','drill_type','location_id','drill_date',
                           'time_alarm_sounded','time_drill_conducted','time_evacuated',
                           'drill_team','drill_team_other',
                           'drill_start_situation','drill_start_situation_other',
                           'weather','weather_other',
                           'participants_number','employees_working_area_num',
                           'evacuation_count','problem_encountered',
                           'problem_encountered_other','evacuate_immediately',
                           'checked_confined_areas','emergency_informed',
                           'equipment_function','evacuated_all',
                           'remain_outside','emergency_team_knowledgeable',
                           'drill_conducted_orderly','evacuation_speed',
                           'procedure_effectiveness','drill_communication',
                           'drill_rating','mitigation',
                           'mitigation_other','comments',
                           'created_by','report_number','count'                      
                          ];

    public function locationdetails()
    {
        return $this->belongsTo('App\Location','location_id');
    }
}
