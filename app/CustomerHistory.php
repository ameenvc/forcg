<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerHistory extends Model
{
    protected $fillable = [
        'customer_id',
        'filled_by_name',
        'filled_by_position',
        'comment',
        'evaluation_date',
        'evaluation_period',
        'overall_score',
        'status',
        'type',
        'answers',
        'is_deleted',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'answers' => 'array'
    ];

}
