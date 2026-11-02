<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithEvents,
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;

class CorrectiveExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data, $columns, $companyName, $logoPath,$commanData;
    public function __construct($commanData, $tab,$logoPath)
    {
        $this->data = $commanData[$tab];
        $this->columns = $commanData['tableDetails'][$tab]['columns_active'];
        $this->companyName = $commanData['clientDetails']->company_name;
        $this->logoPath = $logoPath;
        $this->commanData = $commanData;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data)->values()->map(function ($item, $i) {
            $productFieldValues = $item['ncrFieldValueRelation']->keyBy('field_name');
            $processFieldValues = $item['processNcrFieldValueRelation']->keyBy('field_name');
            $vendorFieldValues = $item['vendorNcrFieldValueRelation']->keyBy('field_name');
            $row = [
                $i + 1,
                $item['issueid'],
                $item['title'],
            ];
            foreach ($this->columns as $col) {
                if($col['category_name'] && $col['category_name']=='product ncr'){
                    $fieldData = $productFieldValues[$col['field_name']] ?? null;
                    $fieldValue = $fieldData->value ?? null;
                }else if($col['category_name'] && $col['category_name']=='process ncr'){
                    $fieldData = $processFieldValues[$col['field_name']] ?? null;
                    $fieldValue = $fieldData->value ?? null;
                }else if($col['category_name'] && $col['category_name']=='vendor ncr'){
                    $fieldData = $vendorFieldValues[$col['field_name']] ?? null;
                    $fieldValue = $fieldData->value ?? null;
                }
                switch ($col->column_name) {
                    case 'category':
                            $cat = $item['cat'] ? $item['cat']->categoryname : '-';
                            if($item['category']=='Legal Requirement')
                                $row[] = $item['category'];
                            elseif($item['category']=='Incident Investigation')
                                $row[] = $item['category'];
                            elseif($item['category']=="Management Review")
                                $row[] = $item['category'];
                            else
                                $row[] = $cat;

                        break;
                    case 'type':
                        $row[] = $item['typ'] ? $item['typ']->name : '-';
                        break;
                    case 'department':
                        $row[] = $item['correctionDept'] ? $item['correctionDept']->departmentname : '-';
                        break;

                    case 'division':
                        $row[] = $item['division'] ? $item['division']->name : '-';
                        break;

                    case 'subdivision':
                        $row[] = $item['area'] ? $item['area']->name : '-';
                        break;

                    case 'raisedby':
                        $row[] = $item['postedByCorrective'] ? $item['postedByCorrective']->name : '-';
                        break;
                    case 'financial loss' :
                        $row[] = $item['financial_loss'] ? $item['financial_loss'] : '-';
                        break;
                    case 'potential cost saving' :
                        $row[] = $item['potential_cost_savings'] ? $item['potential_cost_savings'] : '-';
                        break;
                    case 'responsibility':
                        $responsible_names = isset($this->commanData['userForResponsibility'])
                            ? $this->commanData['userForResponsibility']
                                ->whereIn('email', explode(',', $item['correction_responsibility']))
                                ->pluck('name')
                                ->toArray()
                            : [];
                        $responsible_names = implode(',', $responsible_names);
                        $row[] = $responsible_names ?  $responsible_names : '-';
                        break;
                    case 'email remainder for action':
                        $row[] = $item['frequency'] ? $item['frequency'] : '-';
                        break;
                    case 'due date':
                        $row[] = $item['duedate'] ? date('d-m-Y',strtotime($item['duedate'])) : '-';
                        break;
                    case 'investigation team lead' :
                        $row[] = $item['teamLead'] ? $item['teamLead']->name : '-';
                        break;
                    case 'investigation target date' :
                            $row[] = $item['investigation_date'] ? date('d-m-Y',strtotime($item['investigation_date'])) :'-';
                        break;
                    case 'correction target date':
                        $row[] = $item['correction_target_date'] ? date('d-m-Y',strtotime($item['correction_target_date'])) : '-';
                        break;
                    case 'source' :
                        $row[] = $item['source'] ? $item['source'] : '-';
                       break;
                    case  'added date':
                        $row[] = $item['created_at'] ? date('d-m-Y',strtotime($item['created_at'])) : date('d-m-Y',strtotime($item['postdate']));
                        break;
                    case 'action reviewed by':
                        $row[] = $item['actionReviewedByCorrective'] ? $item['actionReviewedByCorrective']->name : '-';
                        break;
                    case 'action reviewed date':
                        $row[] = $item['action_reviewed_at'] ? date('d-m-Y',strtotime($item['action_reviewed_at'])) : '-';
                        break;
                    case 'closure reviewed by':
                        $row[] = $item['closedByCorrective'] ? $item['closedByCorrective']->name : '-';
                        break;
                    case 'closure reviewed date':
                        $row[] = $item['closed'] ? date('d-m-Y',strtotime($item['closed'])) : '-';
                        break;
                    case 'detection stage':
                        $row[] = $item['categoryDetectionStage'] ? $item['categoryDetectionStage']->name : '-';
                        break;
                    case 'job order number':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'product serial number':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'product name':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'product code':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'batch':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'production line':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'date of manufacture':
                        $row[] = $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-';
                        break;
                    case 'quantity affected':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'process name':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'machine / equipment id':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'operator / shift':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'date of process':
                        $row[] = $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-';
                        break;
                    case 'vendor name':
                        $row[] = $fieldValue && isset($this->commanData['vendors'][$fieldValue]) ? $this->commanData['vendors'][$fieldValue] : '-';
                        break;
                    case 'item serial number':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'purchase order (PO) number':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'receiving report (RR) number':
                        $row[] = $fieldValue ?? '-';
                        break;
                    case 'delivery date':
                        $row[] = $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-';
                        break;
                }
            }

            //Progress stage
            if($item['processstage'] =='Awaiting actions')
                $row[] = $item['processstage'];
            elseif($item['processstage']== 'Correction Proposed- RC Awaiting' || $item['processstage'] == 'Actions Proposed- Review Awaiting' || $item['processstage'] == 'RC Updated- Correction Awaiting')
                $row[] = $item['processstage'];
            elseif($item['processstage']=='Action Reviewed, OK')
                $row[] = 'Action Approved';
            elseif($item['processstage']=='Action Reviewed, Not OK')
               $row[] = "Action Rejected";
            elseif($item['processstage']=='rejected')
                $row[] = "Entry Rejected";
            elseif($item['processstage']=='Need More Action' || $item['processstage']=='Entry Under Review')
                $row[] = $item['processstage'];
            elseif($item['processstage']=='Actions Completed')
                $row[] = $item['processstage'];
            elseif($item['status'] == 'closed' && $item['closed'] <= $item['correction_target_date']) 
                $row[] = 'Closed';
            elseif($item['status']  == 'closed' && $item['closed'] > $item['correction_target_date'])
                $row[] = "Closed After Due";
            else 
                $row[] = '-';

            $row[] = ($item['average_percent'] ?? 0) . '%';
            return $row;
        });
    }
    public function headings(): array
    {
        $header = ['Slno', 'Id', 'Title'];

        foreach ($this->columns as $col) {
            $header[] = ucwords($col->display_label ?? $col->column_name);
        }

        $header[] = 'Process Stage';
        $header[] = 'Action Status';

        return $header;
    }
    //registerEvents 
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {

                $sheet = $event->sheet->getDelegate();

                // Insert rows FIRST
                $sheet->insertNewRowBefore(1, 3);
                if (
                    !empty($this->logoPath) &&
                    file_exists($this->logoPath) &&
                    is_readable($this->logoPath)
                ) {

                    $drawing = new Drawing();
                    $drawing->setName('Logo');
                    $drawing->setPath($this->logoPath);
                    $drawing->setHeight(40);
                    $drawing->setCoordinates('A1');
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                }
                // Set row height AFTER insert
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);
                
                //  Column calculation
                $totalColumns = 3 + count($this->columns) + 2;
                $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);

                $midStart = 4;
                $midEnd = $totalColumns - 2;

                $rightStart = $totalColumns - 1;
                $rightEnd = $totalColumns;
                $progressStageColumn = Coordinate::stringFromColumnIndex($totalColumns - 1);
                //  Merge cells
                $sheet->mergeCells('A1:C2');
                $sheet->mergeCells("A3:{$lastColumn}3");
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($midStart) . '1:' .
                    Coordinate::stringFromColumnIndex($midEnd) . '2'
                );

                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($rightStart) . '1:' .
                    Coordinate::stringFromColumnIndex($rightEnd) . '2'
                );

                //  Set values
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($midStart) . '1',
                    $this->companyName
                );

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($rightStart) . '1',
                    'Exported: ' . now()->format('d-M-Y')
                );

                //  Alignment
                $sheet->getStyle(
                    Coordinate::stringFromColumnIndex($midStart) . '1'
                )->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center')
                    ->setWrapText(true);

                $sheet->getStyle(
                    Coordinate::stringFromColumnIndex($rightStart) . '1'
                )->getAlignment()
                    ->setHorizontal('right')
                    ->setVertical('center');

                // Font
                $sheet->getStyle(
                    Coordinate::stringFromColumnIndex($midStart) . '1'
                )->getFont()->setBold(true)->setSize(14);

                $sheet->getStyle(
                    Coordinate::stringFromColumnIndex($rightStart) . '1'
                )->getFont()->setBold(true);

                $sheet->getColumnDimension('A')->setWidth(12);
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(12);

                // Freeze header
                $sheet->freezePane('A5');

                // Header row styling
                $sheet->getStyle("A4:{$lastColumn}4")->getFont()->setBold(true);

                //  Auto width
                foreach (range('B', $lastColumn) as $col) {
                    $sheet->getColumnDimension($col)->setWidth(20);
                }
                // Specific column widths
                $sheet->getColumnDimension('A')->setWidth(8);   // Slno
                // $sheet->getColumnDimension('B')->setWidth(12);  // ID
                $sheet->getColumnDimension('C')->setWidth(30);  // Title
                $sheet->getColumnDimension($progressStageColumn)->setWidth(30);
                $sheet->getStyle("A4:{$lastColumn}" . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                
                //Color Category
                $categoryColumnIndex = null;
                $typeColumnIndex = null;
                foreach ($this->columns as $index => $col) {
                    if ($col->column_name == 'category') {
                        $categoryColumnIndex = $index + 4;
                    }
                    if ($col->column_name == 'type') {
                        $typeColumnIndex = $index + 4;
                    }
                }
                if ($categoryColumnIndex) {
                    $categoryColumn = Coordinate::stringFromColumnIndex($categoryColumnIndex);
                    foreach ($this->data as $rowIndex => $item) {
                        $excelRow = $rowIndex + 5;
                        $cat = $item['cat'] ? $item['cat']->categoryname : '-';
                        $color = '000000';
                        // Management Review → Green
                        if ($cat == 'Management Review') {
                            $color = '33CC33';
                        // Compliance / Legal Requirement → Red
                        } elseif (
                            $item['category'] == 'Legal Requirement'
                        ) {
                            $color = 'FF0066';
                        // Incident → Orange
                        } elseif (
                            $item['category'] == 'Incident Investigation'
                        ) {
                            $color = 'FFA500';
                        // Corrective Action → Black
                        } elseif ($cat == 'Corrective Action') {
                            $color = '000000';
                        }
                        // Apply text color
                        $sheet->getStyle("{$categoryColumn}{$excelRow}")
                            ->getFont()
                            ->getColor()
                            ->setRGB($color);
                    }
                }
                //Type color
                if ($typeColumnIndex) {
                    $typeColumn = Coordinate::stringFromColumnIndex($typeColumnIndex);
                    foreach ($this->data as $rowIndex => $item) {
                        $excelRow = $rowIndex + 5;
                        // Default colors
                        $txtColor = '25499C';
                        // Dynamic DB colors
                        if ($item['typ']) {
                            $txtColor = str_replace('#', '', $item['typ']->text_color);
                        }
                        $sheet->getStyle("{$typeColumn}{$excelRow}")
                            ->getFont()
                            ->getColor()
                            ->setRGB($txtColor);
                    }
                }
                //Progess Stage
                $progressColumnIndex = $totalColumns - 1;
                $progressColumn = Coordinate::stringFromColumnIndex($progressColumnIndex);
                foreach ($this->data as $rowIndex => $item) {
                    $excelRow = $rowIndex + 5;
                    $stage = $item['processstage'];
                    $color = '000000';
                    if ($stage == 'Awaiting actions') {
                        $color = '737373';
                    } elseif (
                        $stage == 'Correction Proposed- RC Awaiting' ||
                        $stage == 'Actions Proposed- Review Awaiting' ||
                        $stage == 'RC Updated- Correction Awaiting'
                    ) {
                        $color = '25499C';
                    } elseif ($stage == 'Action Reviewed, OK') {
                        $color = '176044';
                    } elseif (
                        $stage == 'Action Reviewed, Not OK' ||
                        $stage == 'rejected'
                    ) {
                        $color = 'AF1E1E';
                    } elseif (
                        $stage == 'Need More Action' ||
                        $stage == 'Entry Under Review'
                    ) {
                        $color = 'AF6F1E';
                    } elseif ($stage == 'Actions Completed') {
                        $color = '25499C';
                    }
                    if (
                        $item['status'] == 'closed' &&
                        $item['closed'] <= $item['correction_target_date']
                    ) {
                        $color = '176044';
                    } elseif (
                        $item['status'] == 'closed' &&
                        $item['closed'] > $item['correction_target_date']
                    ) {
                        $color = 'AF6F1E';
                    }
                    // Apply text color only
                    $sheet->getStyle("{$progressColumn}{$excelRow}")
                        ->getFont()
                        ->getColor()
                        ->setRGB($color);
                }
            }
        ];
    }
}
