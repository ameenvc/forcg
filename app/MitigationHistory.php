<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MitigationHistory extends Model
{
    protected $fillable = [
        'id',
        'risk_id',
        'mitigation_id',
        'existing_likelihood',
        'existing_impact',
        'existing_risk',
        'new_likelihood',
        'new_impact',
        'new_risk',
    ];
}
