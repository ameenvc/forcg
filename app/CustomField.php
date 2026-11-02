<?php

namespace App;
use App\DropdownOption;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $table = "custom_fields";
    protected $fillable = [
        'module_name',
		'field_name',
        'mandatory',
        'sub_module',
		'display_pages',
        'target_pages',
        'created_at',
        'updated_at',
        'field_type',
        'field_placeholder',
        'dropdown_options',
        'deleted',
        'model_name'
    ];
    public function dropdownOption(){
        return $this->hasMany(DropdownOption::class, 'custom_field_id');
    }
}
