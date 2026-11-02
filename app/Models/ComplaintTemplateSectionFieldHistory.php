<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTemplateSectionFieldHistory extends Model
{
    protected $fillable = [
        'complaint_template_section_field_id',
        'previous_display_label',
        'updated_display_label',
        'updated_by'
    ];

    public function complaintTemplateSectionField()
    {
        return $this->belongsTo('App\Models\ComplaintTemplateSectionField');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }
}
