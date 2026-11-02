<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTemplateSection extends Model
{
    protected $fillable = [
        'title',
        'description',
        'complaint_template_id',
        'show_subfield_question',
        'order'
    ];

    public function complaintTemplateSectionFields()
    {
        return $this->hasMany('App\Models\ComplaintTemplateSectionField');
    }

    public function complaintTemplate()
    {
        return $this->belongsTo('App\Models\ComplaintTemplate');
    }
}
