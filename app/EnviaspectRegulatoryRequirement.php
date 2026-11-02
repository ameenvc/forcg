<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnviaspectRegulatoryRequirement extends Model
{
    protected $fillable = [
        'description',
        'weightage'
    ];
}
