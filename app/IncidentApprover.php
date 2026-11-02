<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentApprover extends Model
{
    protected $table = "incident_approvers";
    protected $fillable = [
      'name',
      'email'
    ];
}
