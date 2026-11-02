<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDraftContentRichText extends Model
{
    protected $fillable = [
        'content_id',
        'responsibility',
        'rich_text',
        'draft_id',
        'is_deleted',
        'position'
    ];
    public function governanceHubDraftContent()
    {
        return $this->belongsTo(GovernanceHubDraftContent::class, 'content_id');
    }
}
