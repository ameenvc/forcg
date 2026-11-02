<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LicensePermit extends Model
{
    protected $fillable = [
        'comp_id',
        'name',
        'expirydate',
        'responsible',
        'renewal_requirements',
        'associated_regulations',
        'file'
    ];
    public function compliance()
    {
        return $this->belongsTo(Compliance::class, 'comp_id');
    }
}