<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDraftContent extends Model
{
    protected $fillable = [
        'document_id',
        'title',
        'type',
        'doctype_id',
        'responsibility',
        'rich_text',
        'is_deleted',
        'position',
    ];
    public function governanceHubDraft()
    {
        return $this->belongsTo(GovernanceHubDraft::class, 'document_id');
    }
    public function contentId()
    {
        return $this->hasMany(GovernanceHubDraftContentRichText::class, 'content_id');
    }

    /**
     * Boot method to ensure the position is set when creating a new record
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ensure the document_id is set and calculate position accordingly
            if (!$model->position && $model->document_id) {
                $lastPosition = static::where('document_id', $model->document_id)->max('position') ?? 0;
                $model->position = $lastPosition + 1;
            }
        });
    }
}
