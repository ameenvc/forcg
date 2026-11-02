<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multipleissue extends Model
{
    protected $table = "multipleissues";
    protected $fillable = [
      'id',
      'issue_file'
    ];
}
