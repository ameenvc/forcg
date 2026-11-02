<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunityapprover extends Model
{
    protected $fillable = [
        'name', 'email',
    ];

    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity', 'id', 'opportunityapprover_id');
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
