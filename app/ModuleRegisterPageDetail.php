<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleRegisterPageDetail extends Model
{
    protected $table = "module_register_page_details";
    protected $fillable = [
        'module_name',
        'column_name',
        'column_position',
        'column_filter',
        'is_active',
        'enabled_by_user',
    ];
}
