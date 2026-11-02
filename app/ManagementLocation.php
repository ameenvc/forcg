<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementLocation extends Model
{
    protected $table='management_locations';
    protected $fillable = ['name'];
}
