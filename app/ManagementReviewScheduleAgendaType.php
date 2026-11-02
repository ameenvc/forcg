<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewScheduleAgendaType extends Model
{
    protected $table='management_review_schedules_agenda_types';
    protected $fillable = ['schedule_id','agenda_type_id'];
}
