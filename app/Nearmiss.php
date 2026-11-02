<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Nearmiss extends Model
{
    protected $table = "nearmiss";
    protected $fillable = [
        'count','reporter_type','employeeid','reporter_name',
        'issueid','mobile_number','email','company_name','ip_address',
        'action_file','initial_reviewed_by','initial_reviewed_at',
        'year','initial_reviewed_rejection',
        'notification',
        'type',
        'department',
        'division_id',
        'area_id',
        'title',
        'reportedName',
        'reportedId',
        'file',
        'observation',
        'targetdate',
        'agreedaction',
        'rootcause',
        'correctiveaction',
        'correction',
        'status',
        'postedby',
        'responsibility',
        'processstage',
        'approvement',
        'impl_status',
        'remarks',
        'action_remarks',
        'closed',
        'active',
        'approvedby',
        'closedby',
        'frequency',
        'Monthlyfreq',
        'Weeklyfreq',
        'Dayfreq',
        'area'
    ];
    protected $appends = ["created_at", "updated_at"];
    public function getCreatedAtAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d h:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }
    public function getUpdatedAtAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d h:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }
    public function postedByUser()
    {
        return $this->belongsTo('App\User', 'postedby', 'employeeid');
    }
    public function approvedByUser()
    {
        return $this->belongsTo('App\User', 'approvedby', 'employeeid');
    }
    public function responsible()
    {
        return $this->belongsTo('App\User' , 'responsibility', 'email');
    }
    public function closedByUser()
    {
        return $this->belongsTo('App\User', 'closedby', 'employeeid');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function subdiv()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
    public function initialReviewByUser()
    {
        return $this->hasOne('App\User', 'id', 'initial_reviewed_by');
    }
    public function employeeDetails()
    {
        return $this->hasOne('App\EmployeeDetails', 'emp_number', 'employeeid');
    }

}
