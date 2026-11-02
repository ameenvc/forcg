<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklistNewRecord extends Model
{
    protected $table="audit_checklist_new_records";
    protected $fillable = [
                            'audit_schedule_id','checklist_id','title_id','points_id',
                            'department_id','audit_checklist_id','division_id','area_id',
                            'total_checklist_qns','completed_qns',
                            'remaining_qns','audit_date',
                            'completed_percentage','completed_status',
                            'record_status','count','record_updated_date'

    ];

    public function getChecklistnames()
    {
        return $this->belongsTo('App\AuditChecklist', 'checklist_id', 'id');
    }
}
