<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OhsRiskowner extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'ohs_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function ohs()
    {
        return $this->belongsTo('App\Ohs', 'ohs_id', 'id');
    }
}
