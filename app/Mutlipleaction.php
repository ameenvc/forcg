<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mutlipleaction extends Model
{
    protected $table = "multipleactions";
    protected $fillable = [
      'id',
      'action_file'
    ];
}
