<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = "vendor";

    protected $fillable = [
        'vendor_name',
        'vendor_group',
        'email',
        'scope',
        'country_id',
        'location',
        'contact',
        'Basis_addition',
        //'Iso_standard',
        'Yoe',
        'comments',
        'comments_of_added_person',
        'comments_of_reviewed_person',
        'comments_of_approved_person',
        'trial_order',
        'approved_date',
        'basis_approval',
        'vendor_type',
        'outcome',
        'eval',
        'status',
        'added_by',
        'recommend',
        'score',
        'total',
        'per',
        'evaluated_at',
        'evaluated_by',
        'due_date',
        'is_deleted',
        'created_at',
        'updated_at',
        'reviewed_at',
        'approved_at',
        'evaluation_requested_by',
        'evaluation_requested_time',
        'end_user_id',
        'system_compliance_user_id',
        'supply_chain_representative_user_id',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }

    public function getCreatedAtAttribute($date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)))->format('d-m-Y h:i:s A');
        }
    }

    public function histories()
    {
        return $this->hasMany('App\VendorHistory', 'vendor_id', 'id');
    }

    public function standards()
    {
        return $this->belongsToMany('App\Standards', 'vendor_standards', 'vendors_id', 'standards_id');
    }

    public function evaluatedByUser()
    {
        return $this->belongsTo('App\User', 'evaluated_by', 'id');
    }

    public function requestEvaluatedByUser()
    {
        return $this->belongsTo('App\User', 'evaluation_requested_by', 'id');
    }

    public function endUser()
    {
        return $this->belongsTo('App\User', 'end_user_id', 'id');
    }

    public function systemAndCompliance()
    {
        return $this->belongsTo('App\User', 'system_compliance_user_id', 'id');
    }

    public function supplyChainRepresentative()
    {
        return $this->belongsTo('App\User', 'supply_chain_representative_user_id', 'id');
    }

    public function approvedByUser()
    {
        return $this->belongsTo('App\User', 'approved_by', 'employeeid');
    }

    public function reviewedByUser()
    {
        return $this->belongsTo('App\User', 'reviewed_by', 'employeeid');
    }
}
