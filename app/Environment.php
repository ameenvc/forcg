<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    protected $table = 'environment';

    protected $fillable = [
        'date',
        'team',
        'department',
        'division_id',
        'area_id',
        'activity',
        'reviewed_at',
        'status',
        'proposechanges',
        'useremail',
        'count',
        'location',
        'created_by',
        'reviewed_by',
        'context_id'
    ];

    public function dept()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
}
