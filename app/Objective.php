<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Objective extends Model
{
    use HasJsonRelationships;

    protected $casts = [
        'objective_type_id' => 'array',
    ];

    protected $filalble = [
       'department',
       'objectives',
       'target',
       'target_nature',
       'span',
       'tardate',
       'methodology',
       'resources',
       'responsibility',
       'fromdate',
       'todate',
       'frequency',
       'createdat',
       'trend_val1',
       'trend_val2',
       'trend_val3',
       'trend_val4',
       'trend_val5',
       'postedby',
       'status',
       'parents_id',
       'remarks',
       'proposal',
       'higherremarks',
       'higherproposal',
       'otarget',
       'oachieved',
       'frstatus',
       'CAR',
       'description',
       'reviewed_at',
       'reviewed_by',
       'approved_at',
       'approved_by',
       'count',
       'unit',
       'trend_break',
       'trend_year',
        'baseline'
    ];
    public function dep()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }
    public function area()
    {
        return $this->hasOne('App\Area', 'id', 'area_id');
    }
    // public function objectiveType()
    // {
    //     return $this->hasOne('App\ObjectiveType', 'id', 'objective_type_id');
    // }
    public function objectiveTypes()
    {
        return $this->belongsToJson('App\ObjectiveType','objective_type_id');
    }
    public function report()
    {
        return $this->hasMany('App\ObjReport', 'Oid', 'Oid');
    }
    public function getCreatedAtAttribute($date)
    {
        if ($date != null) {
            //return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)))->format('d-m-Y');
        }
    }

    /**
     * @return BelongsTo
     */
    public function responsiblePersonEmail(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsibility', 'email');
    }

    /**
     * @return BelongsTo
     */
    public function responsiblePersonName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsibility', 'name');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'new_postedby', 'id');
    }
    public function approverDetails(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver', 'id');
    }
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'new_approvedby', 'id');
    }
}
