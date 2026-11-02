<?php

namespace App\Exports\Objective;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithEvents,
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use App\Helpers\Complaint\ComplaintHelper;

class ObjectiveExcelExport implements FromCollection, WithHeadings, WithEvents
{
    protected $mergeRanges = [];

    public function __construct($clientDetails, $data, $headers, $contextData) {
        $this->clientDetails = $clientDetails;
        $this->data = $data;
        $this->headers = $headers;
        $this->contextData = $contextData;
    }

    public function collection() {
        return collect($this->data)->values()->map(function ($d, $i) {
            $row = [];

            // get color based on process stage value
            $color = getProcessStageStyle($d->status, 'excel');
            $color = ltrim($color, '#');

            // sl no
            $row[] = $i+1;

            // objective id
            $richText = new RichText();
            $cellValue = $richText->createTextRun($d->objective_id ?? '-');
            $cellValue->getFont()->getColor()->setARGB($color);

            $row[] = $richText;

            // objective type
            $row[] = $d->objectiveTypes ? $d->objectiveTypes->pluck('name')->implode(', ') : '-';

            // objective name
            $row[] = $d->objectives ?? '-';

            // objective target
            $row[] = $d->otarget ?? '-';

            // objective achieved
            $row[] = $d->oachieved ?? '-';

            // objective target date
            $row[] = $d->tardate ?? '-';

            // responsible users
            $responsibleUsers = getUsersFromEmailsOrNames($d->responsibility);
            $responsibleUsersName = $responsibleUsers->pluck('name')->implode(', ');

            $row[] = $responsibleUsersName ?? '-';

            // process stage
            $richText = new RichText();
            $cellValue = $richText->createTextRun($d->status ?? '-');
            $cellValue->getFont()->getColor()->setARGB($color);

            $row[] = $richText;

            return $row;
        });
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $logoPath = public_path('storage/clientlogos/'.$this->clientDetails->client_logo);
                $totalColumns = count($this->headers);

                $insertExtraRow = 3;

                $department = $this->contextData['department'];
                if ($department) {
                    $insertExtraRow++;
                }

                $division = $this->contextData['division'];
                if ($division) {
                    $insertExtraRow++;
                }

                $subDivision = $this->contextData['subDivision'];
                if ($subDivision) {
                    $insertExtraRow++;
                }

                // Insert rows FIRST
                $sheet->insertNewRowBefore(1, $insertExtraRow+1);
                if (
                    !empty($logoPath) &&
                    file_exists($logoPath) &&
                    is_readable($logoPath)
                ) {

                    $drawing = new Drawing();
                    $drawing->setName('Logo');
                    $drawing->setPath($logoPath);
                    $drawing->setHeight(40);
                    $drawing->setCoordinates('A1');
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                }
                // Set row height AFTER insert
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);

                $lastColumn = Coordinate::stringFromColumnIndex($totalColumns); //name of the column

                $midStart = 4; // column from where title of company start
                $midEnd = $totalColumns - 2; // column where title of company stop

                $rightStart = $totalColumns - 1; // export date column starts
                $rightEnd = $totalColumns; // export date column ends

                //  Merge cells
                $sheet->mergeCells('A1:C2'); // merged A1:C2 such that logo can fit
                $sheet->mergeCells("A3:{$lastColumn}3"); // merge all the cells of row 3 such that space may feel

                $rowAboveTable = $insertExtraRow+1;
                $sheet->mergeCells("A{$rowAboveTable}:{$lastColumn}{$rowAboveTable}"); // merge all the cells of row 3 such that space may feel
                 // merging cells to print title of company
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($midStart) . '1:' .
                    Coordinate::stringFromColumnIndex($midEnd) . '2'
                );

                // merging cells to print export date
                $sheet->mergeCells(
                    Coordinate::stringFromColumnIndex($rightStart) . '1:' .
                    Coordinate::stringFromColumnIndex($rightEnd) . '2'
                );

                //  Set Company Name
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($midStart) . '1',
                    $this->clientDetails->company_name
                );

                // Set export date
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

                if ($department) {
                    $sheet->mergeCells('A4:D4'); // merged A4:D4 Print department info
                    $richText = new RichText();
                    $cellTitle = $richText->createTextRun("Department: ");
                    $cellValue = $richText->createTextRun($department);
                    $cellValue->getFont()->setBold(true);
                    $sheet->setCellValue('A4', $richText);
                    // $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }

                if ($division) {
                    $sheet->mergeCells('A5:D5'); // merged A5:D5 Print division info if exist
                    $richText = new RichText();
                    $cellTitle = $richText->createTextRun("Division: ");
                    $cellValue = $richText->createTextRun($division);
                    $cellValue->getFont()->setBold(true);
                    $sheet->setCellValue('A5', $richText);
                }

                if ($subDivision) {
                    $sheet->mergeCells('A6:D6'); // merged A6:D6 Print Sub Division info if exist
                    $richText = new RichText();
                    $cellTitle = $richText->createTextRun("Sub Division: ");
                    $cellValue = $richText->createTextRun($subDivision);
                    $cellValue->getFont()->setBold(true);
                    $sheet->setCellValue('A6', $richText);
                }

                $headerRow = $rowAboveTable+1;
                // Freeze header
                $sheet->freezePane("A{$headerRow}");
                // Header row styling
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFont()->setBold(true)->setSize(12);

                //  Auto width
                foreach (range('B', $lastColumn) as $col) {
                    $sheet->getColumnDimension($col)->setWidth(20);
                }
                // Specific column widths
                $sheet->getColumnDimension('A')->setWidth(8);   // Slno
                // $sheet->getColumnDimension('B')->setWidth(12);  // ID
                $sheet->getColumnDimension('D')->setWidth(30);  // objective
                $sheet->getStyle("A{$headerRow}:{$lastColumn}" . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $highestRow = $sheet->getHighestRow();

            }
        ];
    }
}
