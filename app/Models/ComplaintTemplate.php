<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'order'
    ];

    public function complaintTemplateSections()
    {
        return $this->hasMany('App\Models\ComplaintTemplateSection');
    }
}
