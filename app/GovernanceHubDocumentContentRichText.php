<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDocumentContentRichText extends Model
{
    protected $fillable = [
        'content_id',
        'responsibility',
        'rich_text',
        'document_id',
        'is_deleted',
        'position'
    ];
    public function governanceHubDocumentContent()
    {
        return $this->belongsTo(GovernanceHubDocumentContent::class, 'content_id');
    }
}
