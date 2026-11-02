<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintPlant extends Model
{
    protected $fillable = [
        'plant_name',
        'is_deleted',
    ];
}
