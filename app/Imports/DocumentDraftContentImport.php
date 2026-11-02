<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentDraftContentImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return $row;
        
    }
}
