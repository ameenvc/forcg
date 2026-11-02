<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'locationable_id', 'locationable_type'];

    public function locationable()
    {
        return $this->morphTo();
    }
}
