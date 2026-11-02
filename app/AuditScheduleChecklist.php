<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditScheduleChecklist extends Model
{
    protected $table = 'audit_schedule_checklists';
    protected $fillable = ['audit_id','checklist_id'];

    public function getChecklistnames()
    {
        return $this->belongsTo('App\AuditChecklist', 'checklist_id', 'id');
    }
}
