<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintUnit extends Model
{
    protected $fillable =[
        'name',
        'is_deleted'
    ];
}
