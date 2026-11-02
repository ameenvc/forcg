<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorStandard extends Model
{
    protected $fillable = [
        'vendors_id',
        'standards_id',
    ];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendors_id', 'id');
    }

    public function standard()
    {
        return $this->belongsTo('App\Standards', 'standards_id', 'id');
    }
}
