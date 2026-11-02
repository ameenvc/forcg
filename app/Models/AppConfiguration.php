<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    protected $fillable = [
        'meta_key',
        'meta_value',
        'updated_by',
    ];
}
