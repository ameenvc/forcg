<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditScheduleArea extends Model
{
    protected $table = 'audit_schedule_areas';
    protected $fillable = ['audit_id','area_id'];
}
