<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewScheduleNote extends Model
{
    protected $table='management_review_schedule_notes';
    protected $fillable = [
                           'schedule_id','agenda_list_id','minute_notes'
                          ];
}
