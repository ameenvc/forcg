<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Closurereviewer extends Model
{
     protected $fillable = [
        'name', 'email',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    public function userWithTrashed()
    {
        return $this->belongsTo('App\User', 'email', 'email')->withTrashed();
    }

}
