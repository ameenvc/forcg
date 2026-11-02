<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Auditor extends Model
{
     protected $fillable = [
        'auditorsname', 'email','certificate',
         'certifications_licensing', 'experience',
         'overall_experience', 'specialization_area'
    ];

    /**
     * @return HasOne
     */
     public function user(): HasOne
     {
         return $this->hasOne(User::class, 'email', 'email');
     }
}

