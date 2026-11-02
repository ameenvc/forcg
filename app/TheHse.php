<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TheHse extends Model
{

    protected $table = "hse";
    protected $fillable = [
       'auditorsname',
       'email',
       'certificate',
       'experience'
    ];
}
