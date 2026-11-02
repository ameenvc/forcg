<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleBladePageDetail extends Model
{
    protected $table = "module_blade_page_details";
    protected $fillable = [
        'module_name',
		'page_name',
        'blade_path',
        'route_path',
		'is_display_page',
        'is_target_page',
        'created_at',
        'updated_at',
        'field_index',
        'structure'
    ];
}
