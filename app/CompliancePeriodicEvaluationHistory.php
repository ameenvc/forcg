<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompliancePeriodicEvaluationHistory extends Model
{
    protected $table = "compliance_periodic_evaluation_history";
    protected $fillable = [
        'compliance_id',
        'periodic_evaluation_by',
        'periodic_evaluation_at',
        'old_status',
        'new_status',
        'created_at',
        'updated_at',
    ];
    public function periodicEvaluationBy()
    {
        return $this->belongsTo('App\User', 'periodic_evaluation_by');
    }
}
