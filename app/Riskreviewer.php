<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Riskreviewer extends Model
{
     protected $fillable = [
        'name', 'email',
    ];

    public function risk()
    {
        return $this->belongsTo('App\Risk', 'id', 'riskreviewer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    public function userWithTrashed()
    {
        return $this->belongsTo('App\User', 'email', 'email')->withTrashed();
    }
}
