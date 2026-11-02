<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObjReport extends Model
{
    protected $table="objreport";

    protected $filalble = [
        'Oid',
        'target',
        'achieved',
        'state',
        'frequency',
        'quarter'
    ];

}
