<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
       'country_id',
       'state_name'
    ];

    public function country()
    {
        return $this->belongsTo('App\Country', 'countries', 'id');
    }
}
