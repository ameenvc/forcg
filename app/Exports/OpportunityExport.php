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
class OpportunityExport implements FromCollection,WithHeadings, WithEvents
{
    protected $data, $companyName, $logoPath,$commanData;
    public function __construct($commanData,$logoPath)
    {
        $this->data = $commanData['datas'];
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
            $row = [
                $i + 1,
                $item['dep_id'],
            ];
            //department
            $row[] = $item['dept'] ? $item['dept']->departmentname : '-';
            $row[] = $item['byresponsibility'] ? $item['byresponsibility']->name.' - '.$item['byresponsibility']->employeeid : '-';
            $row[] = $item['oppname']??'-';
            $row[] = $item['tardate'] ? date('d-m-Y', strtotime($item['tardate'])) : '-';
            //Type
            if ($item['typeRelation'] && count($item['typeRelation'])>0) {
                $types = [];
                foreach ($item['typeRelation'] as $typeRelation) {
                    $types[] = $typeRelation->name;
                }
                $row[] = implode(",\n", $types);
            } else {
                $row[] = '-';
            }
            $row[] = $item['createdat'] ? date('d-m-Y', strtotime($item['createdat'])) : '-';
            $row[] = $item['impact']??'-';
            $row[] = $item['status']??'-';
            if($item['actionstatus']=='Progress-25%')
                $row[] = '25%';
            elseif($item['actionstatus']=='Partially Completed-50%')
                $row[] = '50%';
            elseif($item['actionstatus']=='Completed-100%')
                $row[] = '100%';
            elseif($item['actionstatus']=='Not Started-0%')
                $row[] = '0%';
            else 
                $row[] = '0%';
            return $row;
        });
    }
    public function headings(): array
    {
        $header = ['S.No.', 'ID', 'Department','Responsibility','Opportunity Name','Target Date','Type','Added Date','Positive Impact','Process Stage','Action Status'];
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
                $totalColumns = 11;
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
                
                $targetColumnIndex = 6;
                $today = date('Y-m-d');
                if ($targetColumnIndex) {
                    $targetColumn = Coordinate::stringFromColumnIndex($targetColumnIndex);
                    foreach ($this->data as $rowIndex => $item) {
                        $excelRow = $rowIndex + 5;
                        $color = '000000';
                        // Green
                        if ($item['tardate'] <= $today) {
                            $color = 'af1e1e';
                        } else{
                            $color = '176044';
                        }
                        // Apply text color
                        $sheet->getStyle("{$targetColumn}{$excelRow}")
                            ->getFont()
                            ->getColor()
                            ->setRGB($color);
                    }
                }
                $impactColumnIndex = 9;
                if ($impactColumnIndex) {
                    $impactColumn = Coordinate::stringFromColumnIndex($impactColumnIndex);
                    foreach ($this->data as $rowIndex => $item) {
                        $excelRow = $rowIndex + 5;
                        $color = '000000';
                        // Management Review → Green
                        if ($item['impact'] == 'High') {
                            $color = 'af1e1e';
                        } elseif (
                            $item['impact'] == 'Moderate'
                        ) {
                            $color = 'cc8800';
                        }else{
                            $color = '176044';
                        }
                        // Apply text color
                        $sheet->getStyle("{$impactColumn}{$excelRow}")
                            ->getFont()
                            ->getColor()
                            ->setRGB($color);
                    }
                }
                //Progess Stage
                $progressColumnIndex = $totalColumns-1;
                $progressColumn = Coordinate::stringFromColumnIndex($progressColumnIndex);
                foreach ($this->data as $rowIndex => $item) {
                    $excelRow = $rowIndex + 5;
                    $stage = $item['status'];
                    $color = '000000';
                    if ($stage == 'Approved') {
                        $color = '176044';
                    } elseif (
                        $stage == "Review Pending"
                    ) {
                        $color = 'af6f1e';
                    } elseif (
                        $stage == 'Disapproved' 
                    ) {
                        $color = 'AF1E1E';
                    } elseif (
                        $stage == 'Completed'
                    ) {
                        $color = '25499c';
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