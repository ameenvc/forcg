<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionArea extends Model
{
    protected $fillable = [
        'division_id',
        'area_id'
    ];

    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
}
