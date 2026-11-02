<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Standards extends Model
{
    protected $table = 'standards';
    protected $fillable = [
        'name'
    ];
}
