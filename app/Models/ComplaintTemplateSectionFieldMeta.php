<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTemplateSectionFieldMeta extends Model
{
    protected $fillable = [
        'complaint_template_section_field_id',
        'meta_key',
        'meta_value'
    ];

    public function complaintTemplateSectionField()
    {
        return $this->belongsTo('App\Models\ComplaintTemplateSectionField');
    }

}
