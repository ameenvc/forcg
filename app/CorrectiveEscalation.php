<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrectiveEscalation extends Model
{
    protected $fillable = [
        'corrective_id',
        'last_escalation_sent_at',
        'first_escalation_sent_at'
    ];

    public function corrective()
    {
        return $this->belongsTo(Corrective::class, 'corrective_id');
    }
    public $timestamps = true;
}
