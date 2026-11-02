<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Corrective extends Model
{
    protected $table = "corrective";
    protected $fillable = [
        'count', 'verification_remarks',
        'issueid','is_previously_occurred',
        'category','no_of_occurrence',
        'source', 'previous_ncr_reference',
        'disapproval_reason',
        'type', 'other_previous_ncr_reference',
        'department',
        'title','detection_stage',
        'file',
        'action_file',
        'observation',
        'occurrence',
        'system_related_problems',
        'recent_changes',
        'follow_procedures',
        'contributing_factors',
        'external_factors',
        'issue_ foreseeable',
        'prevent_reason',
        'postdate',
        'duedate',
        'completion_date',
        'year',
        'agreedaction',
        'rootcause',
        'correctiveaction',
        'correction',
        'status',
        'postedby',
        'responsibility',
        'correction_responsibility',
        'notification',
        'processstage',
        'approvement',
        'impl_status',
        'remarks',
        'action_remarks',
        'updated_at',
        'closed',
        'active',
        'approvedby',
        'closedby',
        'frequency',
        'Monthlyfreq',
        'Weeklyfreq',
        'division_id',
        'area_id',
        'target_date',
        'team_lead_id',
        'correction_target_date',
        'correction_status',
        'percent_status',
        'average_percent',
        'contributing_factors_details',
        'external_factors_details',
        'Dayfreq', 'incident_id', 'management_review_schedule_id',
        'financial_loss',
        'potential_cost_savings',
        'action_reviewed_by',
        'correction_department',
        'rootcause_updated_by_investigation_team',
        'correction_proposed',
        'action_reviewed_at','entry_reviewed_by','entry_reviewed_by_date','is_req_rc_analysis_management_review',
        'is_reopened','is_responsible_person_changed','is_team_changed','investigation_date'
    ];

    public function incidentReport(){
        return $this->belongsTo('App\IncidentReport', 'incident_id', 'id');
    }

    public function postedByCorrective()
    {
        return $this->belongsTo('App\User', 'postedby', 'employeeid');
    }

    public function postedByAdmin()
    {
        return $this->belongsTo('App\Admin', 'postedby', 'employeeid');
    }
    public function categoryDetectionStage()
    {
        return $this->belongsTo('App\CorrectiveDetectionStage', 'detection_stage', 'id')->withTrashed();
    }
    public function approvedByCorrective()
    {
        return $this->belongsTo('App\User', 'approvedby', 'employeeid');
    }
    public function actionReviewedByCorrective()
    {
        return $this->belongsTo('App\User', 'action_reviewed_by', 'employeeid');
    }
    public function closedByCorrective()
    {
        return $this->belongsTo('App\User', 'closedby', 'employeeid');
    }
    public function lead()
    {
        return $this->belongsTo('App\User', 'team_lead_id', 'id');
    }
    public function dept()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
    public function cat()
    {
        return $this->hasOne('App\Categories', 'id', 'category')->withTrashed();
    }
    public function correctionDept()
    {
        return $this->hasOne('App\Departments', 'id', 'correction_department');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
    public function investigators()
    {
        return $this->belongsToMany('App\User', 'corrective_investigators', 'corrective_id', 'user_id');
    }

    public function teamLead()
    {
        return $this->belongsTo('App\User', 'team_lead_id', 'id');
    }

    public function typ()
    {
        return $this->belongsTo('App\Type', 'type', 'order');
    }

    public function correctiveInvestigators()
    {
        return $this->hasMany('App\CorrectiveInvestigator', 'corrective_id', 'id');
    }

    public function correctionResponsibility()
    {
        return $this->belongsTo('App\User', 'correction_responsibility', 'email');
    }
    public function moc(){
        return $this->belongsTo('App\ContextCrs','moc_id','id');
    }
    public function ncrFieldValueRelation(){
        return $this->hasMany(NcrFieldValue::class,'corrective_id','id');
    }
    public function previousNcrReferenceRelation(){
        return $this->hasOne(Corrective::class,'id','previous_ncr_reference');
    }
    public function processNcrFieldValueRelation(){
        return $this->hasMany(ProcessNcrFieldValue::class,'corrective_id','id');
    }
    public function vendorNcrFieldValueRelation(){
        return $this->hasMany(VendorNcrFieldValue::class,'corrective_id','id');
    }
    public function entryReviewedByCorrective()
    {
        return $this->belongsTo('App\User', 'entry_reviewed_by', 'id');
    }
    public function escalation()
    {
        return $this->hasOne(CorrectiveEscalation::class);
    }
}
