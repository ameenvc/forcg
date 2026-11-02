<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintEscalation extends Model
{
    protected $fillable = [
        'complaint_id',
        'first_escalation_sent_at',
        'last_escalation_sent_at'
    ];

    public function complaint()
    {
        return $this->belongsTo('App\Complaint', 'complaint_id', 'id');
    }
}
