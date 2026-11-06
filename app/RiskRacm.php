<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskRacm extends Model
{
    protected $fillable = [
        'risk_factor_title',
        'process_id',
        'risk_factor_name',
        'risk_factor_description',
        'low',
        'moderate',
        'high',
        'unit',
        'unit_other',
        'reviewer_id',
        'approver_id',
        'posted_by',
        'status',
        'approved_by',
        'approved_date',
        'rejected_by_approver',
        'reject_reason_by_approver'
    ];

    public function reviewerAssignments()
    {
        return $this->hasMany(RacmReviewerDetail::class, 'racm_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'process_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by', 'id');
    }
    public function approvedBy(){
        return $this->belongsTo(User::class,'approved_by','id');
    }
}
