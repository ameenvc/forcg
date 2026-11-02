<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    protected $fillable = [
       'name',
       'owner_id',
       'division_id'
    ];
    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'owner_id');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function locations()
    {
        return $this->morphMany('App\Location', 'locationable');
    }
}
