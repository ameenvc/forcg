<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintCategory extends Model
{
    protected $fillable = [
        'category_name',
        'is_deleted',
        'plant_id',
    ];
}
