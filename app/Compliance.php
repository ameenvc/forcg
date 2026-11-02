<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{
    protected $table = "compliance";
    protected $primaryKey = 'Cid';
    public $incrementing = true;     
    protected $keyType = 'int'; 
    protected $fillable = [
        'hidden',
        'count',
        'auth_id',
        'comp_status',
        'pro_status',
        'frequency',
        'reason',
        'remarks',
        'issue',
        'version',
        'regulation',
        'authority',
        'requirement',
        'aspect',
        'owner',
        'duedate',
        'incompliance',
        'department',
        'postedby',
        'person_control',
        'person_monitor',
        'visibleto',
        'freq_review',
        'created_at',
        'updatedat',
        'approvedby',
        'approved_at',
        'last_periodical_review_date',
        'closedby', 'draft_reviewed_by','draft_reviewed_at'
    ];
    public function draftReviewedBy(){
        return $this->belongsTo('App\User', 'draft_reviewed_by');
    }
    public function postedByCompliance()
    {
        return $this->belongsTo('App\User', 'postedby', 'employeeid');
    }
    public function approvedByCompliance()
    {
        return $this->belongsTo('App\User', 'approvedby', 'employeeid');
    }
    public function revertedByCompliance()
    {
        return $this->belongsTo('App\User', 'revertedby', 'employeeid');
    }
    public function closedByCompliance()
    {
        return $this->belongsTo('App\User', 'closedby', 'employeeid');
    }
    public function dep()
    {
        return $this->hasOne('App\Departments', 'id', 'owner');
    }
    public function responsible()
    {
        return $this->belongsTo('App\User', 'responsibility', 'email');
    }
    public function licensePermits()
    {
        return $this->hasMany(LicensePermit::class, 'comp_id', 'Cid');
    }
    public function reviewedByCompliance()
    {
        return $this->belongsTo('App\User', 'reviewed_by', 'employeeid');
    }
    public function reviewedEvaluationByCompliance()
    {
        return $this->belongsTo('App\User', 'reviewed_evaluation_by', 'employeeid');
    }
    public function regulatory()
    {
        return $this->belongsTo(RegulatoryAuthority::class, 'authority', 'id')->withTrashed();
    }
    public function legalDocuments()
    {
        return $this->belongsTo(ComplianceLegalDocument::class, 'issue', 'id')->withTrashed();
    }
    public function legalCoordinator()
    {
        return $this->belongsTo(User::class, 'legal_coordinator', 'id');
    }
}