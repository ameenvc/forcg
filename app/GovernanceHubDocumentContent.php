<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDocumentContent extends Model
{
    protected $fillable = [
        'document_id',
        'title',
        'type',
        'doctype_id',
        'responsibility',
        'rich_text',
        'is_deleted',
        'position'
    ];
    public function governanceHub()
    {
        return $this->belongsTo(GovernanceHub::class, 'document_id');
    }
    public function contentId()
    {
        return $this->hasMany(GovernanceHubDocumentContentRichText::class, 'content_id');
    }
}
