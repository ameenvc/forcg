<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrectiveDetectionStage extends Model
{
    use SoftDeletes;
    protected $table = 'corrective_detection_stages';
    protected $fillable = [
        'name',
        'created_by',
        'modified_by',
        'deleted_at'
    ];
    protected $dates = ['deleted_at'];  
    public $timestamps = true;
}
