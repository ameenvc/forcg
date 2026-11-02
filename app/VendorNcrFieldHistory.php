<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorNcrFieldHistory extends Model
{
    use SoftDeletes;
    protected $table = 'vendor_ncr_field_histories';
    protected $fillable = [
        'field_name',
        'previous_name',
        'updated_name',
        'initial_name',
        'modifier',
        'modified_at'
    ];
    protected $dates = ['deleted_at'];  
    public $timestamps = true;
    
    public function modifierDetails(){
        return $this->belongsTo(User::class,'modifier','id');
    }
}
