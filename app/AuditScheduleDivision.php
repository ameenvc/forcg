<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditScheduleDivision extends Model
{
    protected $table = 'audit_schedule_divisions';
    protected $fillable = ['audit_id','division_id'];
}
