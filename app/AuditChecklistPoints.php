<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklistPoints extends Model
{
    protected $table = 'audit_checklist_points';
    protected $fillable = [
                            'points','checklist_title_id',
                            'checklist_id','department_id','division_id','area_id',
                            'purpose','items_verified',
                            'item_verified_records','conformance_id',
                            'remarks','record_created_date','audit_schedule_id'
                                        
    ];

    public function checklistdetails()
    {
        return $this->belongsTo('App\AuditChecklist','checklist_id','id');
    }

    public function departmentdetails()
    {
        return $this->belongsTo('App\Departments','department_id','id');
    }
    public function divisiondetails()
    {
        return $this->belongsTo('App\Division','division_id','id');
    }
    public function areadetails()
    {
        return $this->belongsTo('App\Area','area_id','id');
    }
    public function checklisttitledetails()
    {
        return $this->belongsTo('App\AuditChecklistTitles','checklist_title_id','id');
    }
}
