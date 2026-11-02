<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendorresult extends Model
{
    protected $table = "vendorresult";
    protected $fillable = [
        'percent',
        'result',
        'comment'
    ];
}
