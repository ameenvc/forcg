<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHub extends Model
{
    protected $fillable = [
        'document_name',
        'document_number',
        'views',
        'type',
        'doctype_id',
        'revision',
        'owner',
        'date',
        'prepared_by',
        'reviewed_by',
        'approved_by',
        'change_request_submit',
        'change_request_submitted_date',
        'change_request_submitted_by'

    ];
    public function documentContents()
    {
        return $this->hasMany(GovernanceHubsDocumentContents::class, 'document_id');
    }
    public function department()
    {
        return $this->belongsTo(Departments::class, 'owner', 'id');
    }
    public function documentReviews()
    {
        return $this->hasMany(GovernanceHubsDocumentReview::class, 'document_id');
    }
    public function comments()
    {
        return $this->hasMany(GovernanceHubComment::class,'commentable_id');
    }
    public function governance_hub_comments()
    {
        return $this->morphMany('App\GovernanceHubComment', 'commentable')->where('is_deleted', false)->where('parent_id', 0);
    }

    public function replies()
    {
        return $this->morphMany('App\GovernanceHubComment', 'commentable')->where('is_deleted', false)->where('parent_id', '!=', 0);
    }

    public function preparedbydetails()
    {
        return $this->belongsTo(User::class, 'prepared_by','email');
    }
    public function approvedbydetails()
    {
        return $this->belongsTo(User::class, 'approved_by','email');
    }
    public function doctype()
    {
        return $this->belongsTo(Doctype::class, 'doctype_id', 'id');
    }
}
