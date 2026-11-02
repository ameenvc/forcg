<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
       'sort',
       'country_name',
       'phoneCode'
    ];
}
