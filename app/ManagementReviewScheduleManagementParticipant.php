<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewScheduleManagementParticipant extends Model
{
    protected $table='management_review_schedules_management_participants';
    protected $fillable = ['schedule_id','manage_participant_id'];
}
