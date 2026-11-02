<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorNcrConfigurationField extends Model
{
    use SoftDeletes;
    protected $table = 'vendor_ncr_configuration_fields';
    protected $fillable = [
        'field_name',
        'field_type',
        'display_label',
        'is_mandatory',
        'deleted_at'
    ];
    protected $dates = ['deleted_at'];  
    public $timestamps = true;
}
