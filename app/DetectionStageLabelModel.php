<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetectionStageLabelModel extends Model
{
    use SoftDeletes;
    protected $table = 'detection_stage_label_models';
    protected $fillable = [
        'name',
        'deleted_at'
    ];
    protected $dates = ['deleted_at'];  
    public $timestamps = true;
}
