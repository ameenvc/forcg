<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enviaspect extends Model
{
    protected $fillable = [
        'Eid',
        'enviaspects',
        'ER',
        'potential',
        'A',
        'B',
        'C',
        'D',
        'E',
        'total',
        'HS',
        'status',
        'control',
        'created_at',
        'reviewed_at',
        'department',
        'posted_by',
        'approved_by',
        'reviewed_by',
        'aspect_id',
        'context_id',
        'proc_no',
        'procedure',
        'details'
    ];
    public function dept()
    {
        return $this->hasOne('App\Departments', 'id', 'department');
    }
}
