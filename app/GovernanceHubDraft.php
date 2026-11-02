<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDraft extends Model
{
    protected $fillable = [
        'drafted_by',
        'document_name',
        'type',
        'doctype_id',
        'date',
        'owner',
        'status',
        'approve_rejection_reason',
        'reviewed_rejection_reason',
        'reviewer',
        'approvers',
        'reviewed_by',
        'approved_by',
        'approved_date',
        'procedure_type',
        'change_reason',
        'proposed_changes',
        'document_effects',
        'priority_level',
        'revision',
        'document_number',
        'governance_hub_id',
        'change_request_submitted_date',
        'change_request_submitted_by',
        'change_request_unique_id',
        'review_rejected_by_user',
        'approve_rejected_by_user',
        'is_deleted',
    ];
    public function draftContents()
    {
        return $this->hasMany(GovernanceHubDraftContent::class, 'document_id');
    }
    public function department()
    {
        return $this->belongsTo(Departments::class, 'owner', 'id');
    }
    public function approverdetails()
    {
        return $this->belongsTo(User::class, 'approvers', 'email');
    }
    public function draftedbydetails()
    {
        return $this->belongsTo(User::class, 'drafted_by', 'email');
    }

    public function governance_hub_draft_comments()
    {
        return $this->morphMany('App\GovernanceHubDraftComment', 'commentable')->where('is_deleted', false)->where('parent_id', 0);
    }

    public function replies()
    {
        return $this->morphMany('App\GovernanceHubDraftComment', 'commentable')->where('is_deleted', false)->where('parent_id', '!=', 0);
    }
    public function comments()
    {
        return $this->hasMany(GovernanceHubDraftComment::class,'commentable_id');
    }

    public function documentHubDraftReviewerDetails()
    {
        return $this->hasMany(GovernanceHubDraftReviewerDetail::class, 'draft_id', 'id');
    }
    public function doctype()
    {
        return $this->belongsTo(Doctype::class, 'doctype_id', 'id');
    }
}
