<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintProductDetail extends Model
{
    protected $fillable = [
        'complaint_id', 'item_code','product_description','idno','total_delivery_quantity','affected_quantity','unit','cost_impact',
    ];
}
