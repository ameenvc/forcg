<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    protected $fillable = [
        'question',
        'score',
        'type',
        'is_deleted'
    ];
}
