<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    protected $table = 'scopes';
    protected $fillable = [
                            'name','deleted_at'
                          ];
}
