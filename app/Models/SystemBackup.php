<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemBackup extends Model
{
    protected $fillable = [
        'admin_id',
        'email_id',
        'backup_type',
        'filename',
        'filepath',
        'file_size',
        'status',
        'started_at',
        'completed_at',
        'error_message',
    ];

    protected $casts = [
        'file_size'    => 'integer',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }
}
