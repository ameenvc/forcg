<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDraftComment extends Model
{
    protected $table = "governance_hub_drafts_comments";
    protected $fillable=[
                        'user_id',
                        'parent_id',
                        'comment',
                        'commentable_id',
                        'commentable_type',
                        'new_governance_hub_id',
                        'record_id'
                        ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function admin()
    {
        return $this->belongsTo('App\Admin', 'user_id', 'id');
    }
    public function replies()
    {
        return $this->hasMany('App\GovernanceHubDraftComment', 'parent_id', 'id')->where('is_deleted', false);
    }
    public function commentable()
    {
        return $this->morphTo();
    }
    public function documentdetails()
    {
        return $this->belongsTo('App\GovernanceHubDraft', 'commentable_id', 'id');
    }
}
