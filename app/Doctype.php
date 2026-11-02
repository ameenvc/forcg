<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctype extends Model
{
     protected $fillable = [
        'type_code',
        'typename',
        'is_default',
         'level'
    ];
}
