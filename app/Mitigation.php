<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mitigation extends Model
{
    protected $fillable = [
        'id',
        'risk_id',
        'action_id',
        'owner',
        'department_id',
        'action_name',
        'action_description',
        'priority_level',
        'link_other_risk',
        'due_date',
        'status',
        'progress',
        'resource_required',
        'cost_estimate',
        'created_by',
        'updated_by',
        'reviewed_by',
        'reviewed_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'id');
    }

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id', 'Rid')->withTrashed();
    }

    public function attachments()
    {
        return $this->hasMany(MitigationAttachment::class);
    }

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }
}
