<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Categories extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'categoryname','shortname','enable_source','product_specific_details_required','is_pnc_cat','deleted_at',
        'is_process_cat','is_vendor_cat','enable_detection_stage',
    ];

    public function productNcrAddedRelation()
    {
        return $this->hasMany(ProductNcrConfigurationAddedField::class, 'category_id', 'id');
    }
    public function processNcrAddedRelation()
    {
        return $this->hasMany(ProcessNcrConfigurationAddedField::class, 'category_id', 'id');
    }
    public function vendorNcrAddedRelation()
    {
        return $this->hasMany(VendorNcrConfigurationAddedField::class, 'category_id', 'id');
    }
    protected $table = 'categories';
    protected $dates = ['deleted_at'];
}   
