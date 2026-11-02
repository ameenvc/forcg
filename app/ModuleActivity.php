<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleActivity extends Model
{
    protected $table = 'module_activity';
    protected $fillable = [
        'user_id', 'module_name','last_accessed_at'
    ];
}
