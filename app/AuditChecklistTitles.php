<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklistTitles extends Model
{
    protected $table = 'audit_checklist_titles';
    protected $fillable = [
                            'title','checklist_id','department_id','division_id','area_id',             
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
}
