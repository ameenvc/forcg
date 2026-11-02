<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessNcrConfigurationAddedField extends Model
{
    use SoftDeletes;
    protected $table = 'process_ncr_configuration_added_fields';
    protected $fillable = [
        'field_name',
        'field_type',
        'display_label',
        'is_mandatory',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'modified_at',
        'category_id',
        'field_id',
        'field_label_version'
    ];
    protected $dates = ['deleted_at'];  
    public $timestamps = true;
    
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function fieldId()
    {
        return $this->belongsTo(ProcessNcrConfigurationField::class, 'field_id');
    }
}
