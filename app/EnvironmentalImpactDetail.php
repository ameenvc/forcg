<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnvironmentalImpactDetail extends Model
{
    protected $fillable = [
        'title',
        'impact',
        'details'
    ];
}
