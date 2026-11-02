<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ohs extends Model
{
    use SoftDeletes;
    protected $table = "ohs";
    protected $filalble = [
        'count',
        'actid',
        'risk_status',
        'context_id',
        'department',
        'type',
        'activity',
        'legal',
        'location',
        'team',
        'proposechange',
        'postedby',
        'created_at',
        'reviewed_at',
        'approvedby',
        'division_id',
        'area_id'
    ];
    public function dept()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
    public function loc()
    {
        return $this->hasOne('App\Location', 'id', 'location');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
    public function riskowners()
    {
        return $this->hasMany('App\OhsRiskowner', 'ohs_id', 'id');
    }
    public function employees()
    {
        return $this->hasMany('App\OhsTeam', 'ohs_id', 'id');
    }
}
