<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyResponseSituation extends Model
{
    protected $table = 'emergency_response_situations';
    protected $fillable = ['name','description'];
}
