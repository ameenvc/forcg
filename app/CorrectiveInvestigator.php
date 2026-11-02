<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrectiveInvestigator extends Model
{
    protected $fillable = ['user_id', 'corrective_id'];
    public function corrective()
    {
        return $this->belongsTo('App\Corrective', 'corrective_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
