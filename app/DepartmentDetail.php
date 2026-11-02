<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentDetail extends Model
{
    protected $table = 'department_details';
    protected $fillable = [
                            'name','code','deleted_at'
                          ];
}
