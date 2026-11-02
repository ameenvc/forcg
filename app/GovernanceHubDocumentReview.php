<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDocumentReview extends Model
{
    protected $fillable = [
        'document_id',
        'email',
        'score'
    ];

    public function governancehub()
    {
        return $this->belongsTo('App\GovernanceHub', 'document_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }
}
