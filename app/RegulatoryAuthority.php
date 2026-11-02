<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegulatoryAuthority extends Model
{
    use SoftDeletes;

    protected $table = 'regulatory_authorities';
    protected $fillable = [
        'name',
        'status',
        'no_of_documents',
        'initial_name',
        'updated_name',
        'created_by',
        'modified_by',
        'modified_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'token'
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
    public function complianceLegalDocuments()
    {
        return $this->hasMany(ComplianceLegalDocument::class, 'RAC_id', 'id');
    }

}
