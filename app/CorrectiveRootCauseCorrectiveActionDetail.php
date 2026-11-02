<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrectiveRootCauseCorrectiveActionDetail extends Model
{
    protected $fillable = [
      'title',
      'root_cause',
      'corrective_action'
    ];
}
