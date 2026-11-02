<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubReadRequestDetail extends Model
{
    protected $table = "governance_hub_read_request_details";
    protected $fillable = ['governance_hub_id','user_id',
                           'read_request_submitted_by','read_request_submitted_date',
                           'notification_interval','notification_duedate','review_completed'
                          ];
}
