<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MitigationAttachment extends Model
{
    protected $fillable = ['id', 'mitigation_id', 'attachment'];
}
