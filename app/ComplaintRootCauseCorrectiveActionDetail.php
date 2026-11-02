<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintRootCauseCorrectiveActionDetail extends Model
{
    protected $fillable = [
        'complaint_id',
        'root_cause',
        'corrective_action',
        'department',
        'responsibility',
        'target_date',
    ];
}
