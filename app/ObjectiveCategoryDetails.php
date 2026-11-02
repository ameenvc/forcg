<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObjectiveCategoryDetails extends Model
{
    protected $table = 'objective_category_details';
    protected $fillable = [
        'objective_category', 'objective_name', 'objective_description',
        'objective_target', 'objective_unit','objective_methodology',
        'objective_resources', 
    ];
}
