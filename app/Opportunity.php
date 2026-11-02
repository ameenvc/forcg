<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $table = "opportunity";
    public $timestamps = false;
    protected $fillable = [
        'Oid',
        'count','is_reviewed','is_closed',
        'dep_id',
        'department',
        'oppname',
        'oppdes',
        'tardate',
        'ohs',
        'type',
        'action',
        'responsibility',
        'likelihood',
        'benefit',
        'impact',
        'status',
        'actionstatus',
        'postedby',
        'createdat',
        'updatedat',
        'reviewdate',
        'closed_at',
        'reason',
        'remarks',
        'oppstatus',
        'secondary',
        'ndareason',
        'monthly',
        'twodays',
        'due',
        'file',
        'division_id',
        'area_id',
        'opportunityapprover_id',
        'closurereviewer_id'
    ];

    public function opportunityapprover(){
        return $this->belongsTo('App\User', 'opportunityapprover_id', 'id');
    }

    public function closurereviewer() {
        return $this->belongsTo('App\User', 'closurereviewer_id', 'id');
    }

    public function postedByOpportunity()
    {
        return $this->belongsTo('App\User', 'postedby', 'employeeid');
    }

    public function dept()
    {
        return $this->belongsTo('App\Departments', 'department', 'id');
    }

    public function byresponsibility()
    {
        return $this->belongsTo('App\User', 'responsibility', 'email');
    }

    public function getReviewdateAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }

    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
    public function risk(){
        return $this->belongsTo(Risk::class,'risk_id','Rid');
    }
    public function getTypeRelationAttribute()
    {
        if($this->type=='NULL'){
            return [];
        }
        $ids = array_filter(explode(',', $this->type));
        return OpportunityType::whereIn('id', $ids)->get();
    }
}
