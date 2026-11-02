<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleMyOpenPageDetail extends Model
{
    protected $table = "module_my_open_page_details";
    protected $fillable = [
        'module_name',
        'column_name',
        'column_position',
        'column_filter',
        'is_active',
        'enabled_by_user',
    ];
}
