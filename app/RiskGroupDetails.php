<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskGroupDetails extends Model
{
    protected $table = 'risk_group_details';
    protected $fillable = [
        'group_name', 'risk_category', 'risk_name',
        'risk_desp', 'risk_driver',
        'mitigating_actions', 
    ];
}
