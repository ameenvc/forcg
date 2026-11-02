<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewScheduleParticipant extends Model
{
    protected $table='management_review_schedules_participants';
    protected $fillable = ['schedule_id','participants_id'];
}
