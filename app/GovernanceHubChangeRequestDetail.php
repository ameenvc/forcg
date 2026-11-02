<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubChangeRequestDetail extends Model
{
    protected $fillable = [
        'parent_governance_id',
        'change_requested_date',
        'change_requested_submitted_by_user_id',
        'changes_made',
        'change_requested_completed_status',
        'change_request_completed_date',
        'new_revision',
        'unique_id',
        'reviewers',
        'approvers',
    ];
}
