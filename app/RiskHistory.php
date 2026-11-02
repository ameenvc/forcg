<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskHistory extends Model
{
    protected $table = 'riskhistory';
    protected $fillable = [
        'Rid', 'riskname',
        'impa', 'impa_updated',
        'like', 'like_updated',
        'rank', 'rank_updated', 'risk_owner_id',
        'mitigating_action', 'mitigating_action_updated', 'action'
    ];

    public function risk()
    {
        //return $this->hasOne('App\Risk', 'Rid', 'Rid');
        return $this->belongsTo('App\Risk', 'Rid', 'Rid');
    }

    public function riskOwner()
    {
        return $this->belongsTo('App\User', 'risk_owner_id', 'id')->withTrashed();
    }
}
