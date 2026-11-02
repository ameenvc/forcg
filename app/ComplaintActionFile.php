<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintActionFile extends Model
{
    protected $fillable = [
        'complaint_id','action_file','is_deleted'
     ];
}
