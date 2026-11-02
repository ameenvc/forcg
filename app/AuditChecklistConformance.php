<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditChecklistConformance extends Model
{
    protected $table = 'audit_checklist_conformances';
    protected $fillable = ['stage','classification','description'];
}
