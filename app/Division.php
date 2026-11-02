<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'owner_id',
        'department_id'
    ];

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'owner_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Departments', 'department_id', 'id');
    }

    public function areas()
    {
        return $this->hasMany('App\DivisionArea', 'area_id', 'id');
    }
    public function locations()
    {
        return $this->morphMany('App\Location', 'locationable');
    }
}
