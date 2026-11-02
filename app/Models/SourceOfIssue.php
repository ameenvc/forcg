<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SourceOfIssue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'updated_by',
        'is_deleted'
    ];
}
