<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorHistory extends Model
{
    protected $table = 'vendorhistory';

    protected $fillable = [
        'vendor_id',
        'evaluated_by',
        'approved_by',
        'approved_at',
        'reviewed_at',
        'evaluation_date',
        'evaluation_period',
        'overall_score',
        'action_status',
        'status',
        'type',
        'answers',
        'is_deleted',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'answers' => 'array'
    ];

    public function approvedByUser()
    {
        return $this->belongsTo('App\User', 'approved_by', 'id');
    }

    public function evaluatedByUser()
    {
        return $this->belongsTo('App\User', 'evaluated_by', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id', 'id');
    }
}
