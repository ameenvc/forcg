<?php

namespace App\Exports\Complaint;

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

class ComplaintExcelExport implements FromCollection, WithHeadings, WithEvents
{
    protected $mergeRanges = [];

    public function __construct($clientDetails, $data, $headers, $tab) {
        $this->clientDetails = $clientDetails;
        $this->data = $data;
        $this->headers = $headers;
        $this->tab = $tab;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection() {
        if ($this->tab != 'category') {
            return collect($this->data)->values()->map(function ($d, $i) {
                $fieldRules = json_decode($d->complaint_template_section_field_rules, true);
                $row = [];
                foreach ($this->headers as $key => $header) {
                    $tag = '';
                    switch ($header['name']) {
                        case 'S.No':
                            $columnValue = $i+1;
                            break;
                        case 'Complaint ID';
                            $columnValue = $d->trackno;
                            break;
                        case 'Title';
                            $columnValue = $d->title;
                            break;
                        case 'type':
                            $columnValue = $d->type;
                            break;
                        case 'customer';
                            $columnValue = $d->customerDetails ? $d->customerDetails->customer : $d->customer;
                            if ($this->tab != "category") {
                                $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'customer', 'Customer Name');
                            }
                            break;
                        case 'department';
                            $columnValue = $d->department ? $d->department->departmentname : $d->dept;
                            break;
                        case 'responsibility':
                            $columnValue = $d->responsiblePerson ? $d->responsiblePerson->name : $d->responsibility;
                            break;
                        case 'due date';
                            $columnValue = $d->tardate ? date('d-m-Y', strtotime($d->tardate)) : '-';
                            break;
                        case 'raisedby';
                            $columnValue = $d->raisedByPerson ? $d->raisedByPerson->name : $d->addedby;
                            break;
                        case 'added date':
                            $columnValue = $d->created_at ? date('d-m-Y',strtotime($d->created_at)) : '-';
                            break;
                        case 'complaint received by';
                            $columnValue = $d->recievedByPerson ? $d->recievedByPerson->emp_name : $d->employee;
                            break;
                        case 'complaint received date';
                            $columnValue = $d->crdate ? date('d-m-Y', strtotime($d->crdate)) : '-';
                            break;
                        case 'mode of complaint':
                            $columnValue = ucfirst($d->mode_of_complaint);
                            break;
                        case 'customer phone';
                            $columnValue = $d->customer_phone;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'customerphone', 'Customer Phone');
                            break;
                        case 'customer email';
                            $columnValue = $d->customer_email;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'customeremail', 'Customer Email');
                            break;
                        case 'product':
                            $columnValue = getProductNames(explode(',', $d->product));
                            $columnValue = " ".$columnValue;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'product', 'Product / Service');
                            break;
                        case 'sale order no.';
                            $columnValue = $d->sale_order_no;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'saleorder', 'Sale Order Number');
                            break;
                        case 'delivery no.';
                            $columnValue = $d->delivery_no;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'deliveryno', 'Delivery Number');
                            break;
                        case 'delivery date':
                            $columnValue = $d->delivery_date ? date('d-m-Y', strtotime($d->crdate)) : '-';
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'deliverydate', 'Delivery Date');
                            break;
                            case 'job order no.';
                            $columnValue = $d->job_order_no;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'job_order_no', 'Job Order Number');
                            break;
                        case 'product serial no.';
                            $columnValue = $d->product_serial_no;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'product_serial_no', 'Product Serial Number');
                            break;
                        case 'project name':
                            $columnValue = $d->project_name;
                            $tag = ComplaintHelper::getTagForExport($fieldRules, $header['label'], 'project_name', 'Project Name');
                            break;
                        case 'warranty coverage';
                            $columnValue = $d->warranty_coverage;
                            break;
                        case 'plant';
                            $columnValue = $d->plantDetails ? $d->plantDetails->plant_name : $d->plant;
                            break;
                        case 'complaint category':
                            $columnValue = $d->complaintCategory ? $d->complaintCategory['category_name'] : $d->complaint_category_id;
                            break;
                        case 'vendor';
                            $columnValue = isset($d->vendor) ? $d->vendor['vendor_name'] : $d->vendor_id;
                            break;
                        case 'division';
                            $columnValue = $d->division ? $d->division->name : $d->division_id;;
                            break;
                        case 'subdivision':
                            $columnValue = $d->subDivision ? $d->subDivision->name : $d->area_id;;
                            break;
                        case 'financial loss';
                            $columnValue = $d->financial_loss;
                            break;
                        case 'potential cost saving';
                            $columnValue = $d->potential_cost_savings;
                            break;
                        case 'email reminder for action':
                            $columnValue = $d->eremainder;
                            break;
                        case 'investigation team lead';
                            $columnValue = $d->teamLead ? $d->teamLead->name : $d->team_lead_id;
                            break;
                        case 'investigation target date';
                            $columnValue = $d->investigation_date ? date('d-m-Y', strtotime($d->investigation_date)) : '-';
                            break;
                        case 'reviewed by':
                            $columnValue = $d->reviewedBy ? $d->reviewedBy->name : $d->reviewed_by;
                            break;
                        case 'reviewed date';
                            $columnValue = $d->reviewedat ? date('d-m-Y',strtotime($d->reviewedat)) : '-';
                            break;
                        case 'closure reviewed by';
                            $columnValue = $d->closedBy ? $d->closedBy->name : $d->closed_by;
                            break;
                        case 'closure reviewed date':
                            $columnValue = $d->closed_at ? date('d-m-Y',strtotime($d->closed_at)) : '-';
                            break;
                        case 'issue authenticity':
                            $columnValue = $d->issue_authenticity;
                            break;
                        case 'source of issue':
                            $columnValue = $d->source_of_issue;
                            break;
                        case 'Process Stage';
                            $columnValue = getProcessStageValue($d->status);
                            break;
                        case 'Action Status';
                            $columnValue = $d->average_percent.'%';
                            break;
                        default:
                            $columnValue = 'invalid';
                            break;
                    }
                    if ($tag) {
                        $richText = new RichText();

                        $cellValue = $richText->createTextRun($columnValue ?? '-');
                        $richText->createText("\n");

                        $cellTag = $richText->createTextRun('('.$tag.')');
                        $cellTag->getFont()->setItalic(true);
                        $cellTag->getFont()->setSize(8);
                        $cellTag->getFont()->getColor()->setARGB('808080');

                        $row[] = $richText;
                    } else {
                        $row[] = $columnValue ?? '-';
                    }
                }
                return $row;
            });
        } else {
            $dat = collect();
            $row = 5; // Assuming row starts after logo and header
            foreach ($this->data as $i => $d) {
                $startRow = $row;
                foreach ($d->products as $key => $product) {
                    $dat->push([
                        $i+1,
                        $d->trackno ?? '-',
                        $d->title ?? '-',
                        $d->type ?? '-',
                        $d->customerDetails ? $d->customerDetails->customer : ($d->customer ?? '-'),
                        $d->responsiblePerson ? $d->responsiblePerson->name : ($d->responsibility ?? '-'),
                        $d->department ? $d->department->departmentname : ($d->dept ?? '-'),
                        $d->tardate ? date('d-m-Y', strtotime($d->tardate)) : '-',
                        $product->cost_impact ?? '-',
                        getComplaintCategoriesFromStringIds($product->complaint_category) ?? '-',
                        getProcessStageValue($d->status),
                        $d->average_percent.'%',
                    ]);
                    $row++;
                }
                $endRow = $row - 1;
                if ($endRow > $startRow) {
                    $this->mergeRanges[] = "A{$startRow}:A{$endRow}";
                    $this->mergeRanges[] = "B{$startRow}:B{$endRow}";
                    $this->mergeRanges[] = "C{$startRow}:C{$endRow}";
                    $this->mergeRanges[] = "D{$startRow}:D{$endRow}";
                    $this->mergeRanges[] = "E{$startRow}:E{$endRow}";
                    $this->mergeRanges[] = "F{$startRow}:F{$endRow}";
                    $this->mergeRanges[] = "G{$startRow}:G{$endRow}";
                    $this->mergeRanges[] = "H{$startRow}:H{$endRow}";
                    $this->mergeRanges[] = "K{$startRow}:K{$endRow}";
                    $this->mergeRanges[] = "L{$startRow}:L{$endRow}";
                }
            }
            return $dat;
        }
    }

    public function headings(): array
    {
        return $labels = array_column($this->headers, 'label');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $logoPath = public_path('storage/clientlogos/'.$this->clientDetails->client_logo);
                $totalColumns = count($this->headers);

                                // Insert rows FIRST
                $sheet->insertNewRowBefore(1, 3);
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
                $sheet->getStyle("A4:{$lastColumn}" . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $highestRow = $sheet->getHighestRow();
                $typeColumn = null;
                $dueDateColumn = null;
                $warrantyCoverageColumn = null;
                $processStageColumn = null;

                foreach ($this->headers as $index => $header) {
                    switch ($header['label']) {
                        case 'Type':
                            $typeColumn = Coordinate::stringFromColumnIndex($index + 1);
                            break;
                        case 'Due Date':
                            $dueDateColumn = Coordinate::stringFromColumnIndex($index + 1);
                            break;
                        case 'Warranty Coverage':
                            $warrantyCoverageColumn = Coordinate::stringFromColumnIndex($index + 1);
                            break;
                        case 'Process Stage':
                            $processStageColumn= Coordinate::stringFromColumnIndex($index + 1);
                            break;
                        default:
                            # code...
                            break;
                    }
                }

                for ($row = 5; $row <= $highestRow; $row++) { // Data starts from row 5
                    if ($typeColumn) {
                        $type = trim($sheet->getCell("{$typeColumn}{$row}")->getValue());
                        $color = getProductTypeStyle($type, 'excel');
                        $sheet->getStyle("{$typeColumn}{$row}")
                            ->getFont()
                            ->getColor()
                            ->setARGB('FF' . ltrim($color, '#'));
                    }
                    if ($dueDateColumn) {
                        $dueDate = trim($sheet->getCell("{$dueDateColumn}{$row}")->getValue());
                        if ($dueDate != '-') {
                            $color = getDueDateStyle($dueDate, 'excel');
                            $sheet->getStyle("{$dueDateColumn}{$row}")
                                ->getFont()
                                ->getColor()
                                ->setARGB('FF' . ltrim($color, '#'));
                        }
                    }
                    if ($warrantyCoverageColumn) {
                        $warrantyCoverage = trim($sheet->getCell("{$warrantyCoverageColumn}{$row}")->getValue());
                        $color = getWarrantyCoverageStyle($warrantyCoverage, 'excel');
                        $sheet->getStyle("{$warrantyCoverageColumn}{$row}")
                            ->getFont()
                            ->getColor()
                            ->setARGB('FF' . ltrim($color, '#'));
                    }
                    if ($processStageColumn) {
                        $processStage = trim($sheet->getCell("{$processStageColumn}{$row}")->getValue());
                        $color = getProcessStageStyle($processStage, 'excel');
                        $sheet->getStyle("{$processStageColumn}{$row}")
                            ->getFont()
                            ->getColor()
                            ->setARGB('FF' . ltrim($color, '#'));
                    }
                }

                // merging cell based on complaint category
                foreach ($this->mergeRanges as $range) {
                    $event->sheet->getDelegate()->mergeCells($range);
                }
            }
        ];
    }
}
