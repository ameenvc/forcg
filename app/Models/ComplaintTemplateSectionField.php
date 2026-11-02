<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTemplateSectionField extends Model
{
    protected $fillable = [
        'complaint_template_section_id',
        'enable_field',
        'field_name',
        'field_type',
        'default_display_label',
        'field_display_label',
        'is_multiple',
        'field_is_mandatory',
        'updated_by',
        'order'
    ];

    public function complaintTemplateSectionFieldHistories()
    {
        return $this->hasMany('App\Models\ComplaintTemplateSectionFieldHistory');
    }

    public function complaintTemplateSectionFieldMetas()
    {
        return $this->hasMany('App\Models\ComplaintTemplateSectionFieldMeta');
    }

    public function complaintTemplateSection()
    {
        return $this->belongsTo('App\Models\ComplaintTemplateSection');
    }
}
