<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyResponseMitigationDetail extends Model
{
    protected $table = 'emergency_response_mitigation_details';
    protected $fillable = ['name'];
}
