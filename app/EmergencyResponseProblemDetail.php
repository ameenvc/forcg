<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyResponseProblemDetail extends Model
{
    protected $table = 'emergency_response_problem_details';
    protected $fillable = ['name'];
}
