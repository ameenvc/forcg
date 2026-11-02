<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDraftReviewerDetail extends Model
{
    protected $table = "governance_hub_drafts_reviewer_details";
    protected $fillable = ['draft_id','reviewer_id',
                           'reviewed_status','reviewed_date',
                           'draft_rejected','draft_reject_reason','new_governance_hub_id'
                          ];

    public function reviewerdetails()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

}
