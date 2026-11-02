<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMyOpenColumnPreference extends Model
{
    protected $table = "user_my_open_column_preferences";
    protected $fillable = [
        'module_name',
        'column_name',
        'category_name',
        'column_position',
        'column_filter',
        'user_id','field_name',
        'is_dragdropable',
    ];
}
