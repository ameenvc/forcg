<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NcrFieldValue extends Model
{
    use SoftDeletes;
    protected $table = 'ncr_field_values';
    protected $fillable = [
        'corrective_id','category_id','field_id','field_name',
        'display_label','value','deleted_at'
    ];
    protected $dates = ['deleted_at'];  
    public $timestamps = true;
    public function savedProductFieldRelation(){
        $this->belongsTo(ProductNcrConfigurationAddedField::class,'id','field_id');
    }
}
