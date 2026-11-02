<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wastetarget extends Model
{
    protected $fillable = [
       'items',
       'qty',
       'approvers_id'
    ];
    public function approver()
    {
        return $this->belongsTo('App\Wasteapprover', 'approvers_id', 'id');
    }
}
