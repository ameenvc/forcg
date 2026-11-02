<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklist extends Model
{
    protected $table = 'audit_checklist';
    protected $fillable = [
                            'checklist_name',
                            'department_id','count','division_id','area_id',
                            'added_by_user_id','updated_by_user_id','delete_reason',
                            'is_deleted','deleted_by_user_id','status','approved_by_user_id',
                            'approved_date','status','suggest_improvements','suggest_improvements_by_user_id','delete_status'
                          ];

                        
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
    public function userdetails()
    {
        return $this->belongsTo('App\User','approved_by_user_id','id');
    }
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }
}
