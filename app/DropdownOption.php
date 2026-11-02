<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropdownOption extends Model
{
    protected $table = 'dropdown_options';
    protected $fillable = [
        'custom_field_id',
        'option',
        'is_deleted'
    ];
}
