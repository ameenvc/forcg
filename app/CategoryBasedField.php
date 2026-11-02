<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryBasedField extends Model
{
    protected $table = "category_based_fields";
    protected $fillable = [
        'module_name',
        'column_name',
        'column_position',
        'column_filter',
        'is_active',
        'is_dragdropable',
        'field_name','category_name',
    ];
}
