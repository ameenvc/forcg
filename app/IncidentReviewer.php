<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentReviewer extends Model
{

    protected $table = "incident_reviewer";
    protected $fillable = [
      'name',
      'email'
    ];
}
