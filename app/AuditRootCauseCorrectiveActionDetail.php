<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditRootCauseCorrectiveActionDetail extends Model
{
    protected $table = 'audit_root_cause_corrective_action_details';
    protected $fillable = ['title','root_cause','corrective_action'];
}
