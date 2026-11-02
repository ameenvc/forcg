<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
     protected $fillable = [
        'name',
        'text_color',
        'background_color',
        'details',
        'order',
         'is_manage_workflow'
    ];
}
