<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleReportDetail extends Model
{
    protected $table = "module_report_details";
    protected $fillable = [
        'module_name',
        'file_name',
    ];
}
