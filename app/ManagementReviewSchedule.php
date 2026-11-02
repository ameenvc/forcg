<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementReviewSchedule extends Model
{
    protected $table='management_review_schedules';
    protected $fillable = [
                           'date','time','location','agenda_type',
                           'agenda_points','agenda_point_detail_type','participants',
                           'management_participants','management_participants_other','management_participants_other_email',
                           'added_by','meeting_cancel_reason','status'
                          ];

    public function addedby()
    {
        return $this->belongsTo('App\User','added_by');
    }

    public function managementLocation()
    {
        return $this->belongsTo('App\ManagementLocation','location');
    }
}