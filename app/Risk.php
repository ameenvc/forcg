<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
class Risk extends Model
{
    use SoftDeletes;
    use HasJsonRelationships;
    public $timestamps = false;
    protected $fillable = [
        'Rid',
        'count',
        'grp_id',
        'riskgroup',
        'riskarea',
        'riskname',
        'riskdec',
        'riskdri',
        'existing_controls',
        'date',
        'riskscr',
        'impa',
        'like',
        'rank',
        'riskreviewer_id',
        'division_id',
        'area_id',
        'status',
        'risk_response',
        'mitigating_action',
        'risk_owner',
        'action_target',
        'action_status',
        'actionst',
        'review_date',
        'remarks',
        'reason',
        'disapprovereason',
        'postedby',
        'reviewed_by',
        'approved_by',
        'created_at',
        'updated_at',
        'periodic_review_date',
        'next_review_date'
    ];

    public function riskreviewer()
    {
        return $this->hasOne('App\User', 'id', 'riskreviewer_id')->withTrashed();
    }


    public function postedByRisk()
    {
        return $this->hasOne('App\User', 'employeeid', 'postedby')->withTrashed();
    }

    public function approvedByRisk()
    {
        return $this->hasOne('App\User', 'employeeid', 'approved_by')->withTrashed();
    }
    public function reviewedByRisk()
    {
        return $this->belongsTo('App\User', 'reviewed_by', 'employeeid')->withTrashed();
    }
    public function department()
    {
        return $this->hasOne('App\Departments', 'id', 'riskarea');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
    public function history()
    {
        return $this->belongsTo('App\RiskHistory', 'Rid', 'Rid');
    }
    public function getRiskOwnerUsersAttribute()
    {
        if (empty($this->risk_owner)) {
            return collect();
        }
        $emails = array_filter(array_map('trim', explode(',', $this->risk_owner)));
        return User::whereIn('email', $emails)->get();
    }
    public function getReviewDateAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }

    public function mitigations()
    {
        return $this->hasMany(Mitigation::class, 'risk_id', 'Rid');
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'risk_id', 'Rid');
    }

    public function mitigationHistories()
    {
        return $this->hasMany(MitigationHistory::class, 'risk_id', 'Rid');
    }
    public function comments() {
        return $this->hasMany(RiskComment::class, 'commentable_id','Rid')->where('parent_id', 0)->where('is_deleted', false);
    }

    public function replies() {
        return $this->hasMany(RiskComment::class, 'commentable_id','Rid')->where('parent_id', '!=', 0)->where('is_deleted', false);
    }
}
