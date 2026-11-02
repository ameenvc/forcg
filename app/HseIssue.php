<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HseIssue extends Model
{
    protected $table = "hseissues";
    protected $fillable = [
        'count',
        'issueid',
        'action_file',
        'year',
        'notification',
        'category',
        'category_id',
        'type',
        'department',
        'division_id',
        'area_id',
        'title',
        'file',
        'observation',
        'postdate',
        'duedate',
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
        'last_reviewed_at',
        'Dayfreq',
        'area','created_at','updated_at'
    ];
    public function postedBy()
    {
        return $this->hasOne('App\User' , 'employeeid', 'postedby');
    }
    public function responsible()
    {
        return $this->belongsTo('App\User' , 'responsibility', 'email');
    }
    public function approvedByHse()
    {
        return $this->hasOne('App\User' , 'employeeid', 'approvedby');
    }
    public function closedByHse()
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
    public function hseCategory()
    {
        return $this->belongsTo('App\HseCategory', 'category_id', 'id');
    }
    public function departmentHse()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
    /**
     * Get the category name.
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->hseCategory ? $this->hseCategory->categoryname : '';
    }
}
