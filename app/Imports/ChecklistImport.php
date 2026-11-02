<?php

namespace App\Imports;

use App\AuditChecklist;
use App\AuditChecklistPoints;
use App\AuditChecklistTitles;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChecklistImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return $row;
        
    }
}
