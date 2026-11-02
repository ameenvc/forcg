<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceLegalDocument extends Model
{
    use SoftDeletes;

    protected $table = 'compliance_legal_documents';
    protected $fillable = [
        'name',
        'status',
        'RAC_id',
        'created_by',
        'modified_by',
        'modified_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at'
    ];
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
    public function appover()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    public function regulatoryAuthority()
    {
        return $this->belongsTo(RegulatoryAuthority::class, 'RAC_id');
    }
}
