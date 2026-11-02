<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientDetail extends Model
{
    protected $table = 'client_details';
    protected $fillable = [
        'company_name','client_logo','appname_to_display'
    ];
}
