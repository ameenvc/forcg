<?php

namespace App\Exports\Complaint;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Helpers\Complaint\ComplaintHelper;

class ComplaintWordExport {

    public function __construct($clientDetails, $data, $headers, $tab) {
        $this->clientDetails = $clientDetails;
        $this->data = $data;
        $this->headers = $headers;
        $this->tab = $tab;
    }

    public function generate(){
        $phpWord = new PhpWord();
        $orientation = count($this->headers) < 9 ? 'portrait' : 'landscape';    // orientation based on no of columns
        $paperSize = count($this->headers) > 20 ? 'a1' : (count($this->headers) > 13 ? 'a2' : (count($this->headers) > 10 ? 'a3' : 'a4')); // paper size

        $pageSizeW = 11906;
        $pageSizeH = 16838;
        if($paperSize == 'a1'){
            $pageSizeH = 47679;
            $pageSizeW = 60000;
        }elseif($paperSize == 'a2'){
            $pageSizeW = 33638;
            $pageSizeH = 23811;
        }elseif($paperSize == 'a3'){
            $pageSizeH = 16838;
            $pageSizeW = 23811;
        }elseif($orientation == 'landscape' && $paperSize == 'a4'){
            $pageSizeW = 16838;
            $pageSizeH = 11906;
        }

        $section = $phpWord->addSection([
            'orientation' => $orientation,
            'pageSizeW'   => $pageSizeW,
            'pageSizeH'   => $pageSizeH,

            'marginTop'    => 720,
            'marginBottom' => 720,
            'marginLeft'   => 720,
            'marginRight'  => 720,

            'headerHeight' => 100,
        ]);

        $this->addDocumentHeader($section, $pageSizeW, $paperSize);
        $this->addTable($section, $pageSizeW);

        $filePath = storage_path($this->tab.'_complaint.docx');
        IOFactory::createWriter($phpWord, 'Word2007')->save($filePath);

        return $filePath;
    }

    private function addDocumentHeader($section, $pageSizeW, $paperSize)
    {
        $documentHeader = $section->addHeader();
        $usedWidth = intval($pageSizeW - 1440);

        // FIX: Define the table width and set alignment inside the style array
        $table = $documentHeader->addTable([
            'cellMargin'  => 0, // Keeps structural items perfectly flush to margins
        ]);

        $table->addRow();

        $logoPath = public_path('storage/clientlogos/'.$this->clientDetails->client_logo);

        // Logo (Left aligned inside its cell)
        $cell1 = $table->addCell(intval($usedWidth*0.25));
        if (!empty($this->clientDetails->client_logo) && file_exists($logoPath)) {
            // FIX: Add left alignment to the image container if needed
            $cell1->addImage($logoPath, [
                'width' => 80,
                'height' => 40,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT
            ]);
        }

        // Company Name (Centered)
        $cell2 = $table->addCell(intval($usedWidth*0.5));
        $cell2->addText(
            $this->clientDetails->company_name,
            ['bold' => true, 'size' => 14],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        // Export Date (Right aligned)
        $cell3 = $table->addCell(intval($usedWidth*0.25));
        $cell3->addText(
            'Exported: '.date('d-m-Y'),
            ['size' => 10],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
        );
    }

    private function addTable($section, $pageSizeW) {
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50
        ]);

        // Calculate dynamic column width
        $totalColumns = count($this->headers);

        $availableWidth = $pageSizeW - 1440;
        $colWidth = intval($availableWidth / $totalColumns);

        //  Styles
        $fontStyle = ['size' => 7];
        $headerFont = ['bold' => true, 'size' => 7];
        $centerAlign = ['alignment' => 'center'];
        $cellStyle = ['valign' => 'center'];
        $cellVMergeStart = ['vMerge' => 'restart', 'valign' => 'center'];
        $cellVMergeContinue = ['vMerge' => 'continue'];

        // =========================
        //  HEADER ROW
        // =========================
        $table->addRow(null, ['tblHeader' => true]);

        foreach ($this->headers as $col) {
            $table->addCell($colWidth, $cellStyle)
                ->addText(
                    ucfirst($col['label'] ?? $col['name']),
                    $headerFont,
                    $centerAlign
                );
        }

        // =========================
        //  DATA ROWS
        // =========================
        foreach ($this->data as $i => $d) {
            $fieldRules = json_decode($d->complaint_template_section_field_rules, true);
            $rowspan = max($d->products->count(),1);

            if ($this->tab == "category" && !$d->products->isEmpty() && $rowspan > 1) {
                foreach ($d->products as $index => $product) {
                    $table->addRow(null, [
                        'cantSplit' => true
                    ]);
                    if ($index == 0) {
                        //sl no
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($i+1), $fontStyle, $centerAlign);

                        //complaint id
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->trackno), $fontStyle, $centerAlign);

                        //title
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->title), $fontStyle, $centerAlign);

                        //type
                        [$fColor, $bgColor] = getProductTypeStyle($d->type, 'word');
                        $fStyle['size'] = 7;
                        $fStyle['color'] = $fColor;
                        $fStyle['bgColor'] = $bgColor;
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->type), $fStyle, $centerAlign);

                        //customer
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->customerDetails ? $d->customerDetails->customer : $d->customer), $fontStyle, $centerAlign);

                        //responsibility
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->responsiblePerson ? $d->responsiblePerson->name : $d->responsibility), $fontStyle, $centerAlign);

                        //department
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->department ? $d->department->departmentname : $d->dept), $fontStyle, $centerAlign);

                        //due date
                        [$fColor, $bgColor] = getDueDateStyle($d->tardate, 'word');
                        $fStyle['size'] = 7;
                        $fStyle['color'] = $d->tardate ? $fColor : '';
                        $fStyle['bgColor'] = $d->tardate ? $bgColor : '';
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->tardate ? date('d-m-Y', strtotime($d->tardate)) : '-'), $fStyle, $centerAlign);
                    } else {
                        for ($i=0; $i <8 ; $i++) {
                            $table->addCell($colWidth, $cellVMergeContinue);
                        }
                    }

                    //cost impact
                    $table->addCell($colWidth, $cellStyle)
                        ->addText($this->safe($product->cost_impact ?? ''), $fontStyle, $centerAlign);
                    //complaint category
                    $table->addCell($colWidth, $cellStyle)
                        ->addText($this->safe(getComplaintCategoriesFromStringIds($product->complaint_category) ?? ''), $fontStyle, $centerAlign);

                    if ($index == 0) {
                        //process stage
                        [$fColor, $bgColor] = getProcessStageStyle($d->status, 'word');
                        $fStyle['size'] = 7;
                        $fStyle['color'] = $fColor;
                        $fStyle['bgColor'] = $bgColor;

                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe(getProcessStageValue($d->status)), $fStyle, $centerAlign);

                        //action status
                        $table->addCell($colWidth, $cellVMergeStart)
                            ->addText($this->safe($d->average_percent.'%'), $fontStyle, $centerAlign);
                    } else {
                        for ($i=0; $i <2 ; $i++) {
                            $table->addCell($colWidth, ['vMerge' => 'continue']);
                        }
                    }
                }
            } else {
                $table->addRow(null, [
                    'cantSplit' => true
                ]);
                foreach ($this->headers as $key => $header) {
                    $tag = "";
                    $fontStyle = ['size' => 7];
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
                            [$fColor, $bgColor] = getProductTypeStyle($d->type, 'word');
                            $fontStyle['color'] = $fColor;
                            $fontStyle['bgColor'] = $bgColor;
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
                            if ($d->tardate) {
                                $columnValue = date('d-m-Y', strtotime($d->tardate));
                                [$fColor, $bgColor] = getDueDateStyle($d->tardate, 'word');
                            } else {
                                $columnValue = '';
                                [$fColor, $bgColor] = ['', ''];
                            }
                            $fontStyle['color'] = $fColor;
                            $fontStyle['bgColor'] = $bgColor;
                            break;
                        case 'raisedby';
                            $columnValue = $d->raisedByPerson ? $d->raisedByPerson->name : $d->addedby;
                            break;
                        case 'added date':
                            $columnValue = $d->created_at ? date('d-m-Y',strtotime($d->created_at)) : '';
                            break;
                        case 'complaint received by';
                            $columnValue = $d->recievedByPerson ? $d->recievedByPerson->emp_name : $d->employee;
                            break;
                        case 'complaint received date';
                            $columnValue = $d->crdate ? date('d-m-Y', strtotime($d->crdate)) : '';
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
                            $columnValue = $d->delivery_date;
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
                            [$fColor, $bgColor] = getWarrantyCoverageStyle($d->warranty_coverage, 'word');
                            $fontStyle['color'] = $fColor;
                            $fontStyle['bgColor'] = $bgColor;
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
                            $columnValue = $d->investigation_date ? date('d-m-Y', strtotime($d->investigation_date)) : '';
                            break;
                        case 'reviewed by':
                            $columnValue = $d->reviewedBy ? $d->reviewedBy->name : $d->reviewed_by;
                            break;
                        case 'reviewed date';
                            $columnValue = $d->reviewedat ? date('d-m-Y',strtotime($d->reviewedat)) : '';
                            break;
                        case 'closure reviewed by';
                            $columnValue = $d->closedBy ? $d->closedBy->name : $d->closed_by;
                            break;
                        case 'closure reviewed date':
                            $columnValue = $d->closed_at ? date('d-m-Y',strtotime($d->closed_at)) : '';
                            break;
                        case 'issue authenticity':
                            $columnValue = $d->issue_authenticity;
                            break;
                        case 'source of issue':
                            $columnValue = $d->source_of_issue;
                            break;
                        case 'cost impact':
                            $costImpact = $d->products->pluck('cost_impact')->toArray();
                            $columnValue = count($costImpact) == 1 ?  $costImpact[0]: '-';
                            break;
                        case 'product complaint category':
                            $complaintCategories = $d->products->pluck('complaint_category')->toArray();
                            $columnValue = count($complaintCategories) == 1 ?  getComplaintCategoriesFromStringIds($complaintCategories[0]): '-';
                            break;
                        case 'Process Stage';
                            $columnValue = getProcessStageValue($d->status);
                            [$fColor, $bgColor] = getProcessStageStyle($d->status, 'word');
                            $fontStyle['color'] = $fColor;
                            $fontStyle['bgColor'] = $bgColor;
                            break;
                        case 'Action Status';
                            $columnValue = $d->average_percent.'%';
                            break;
                        default:
                            $columnValue = 'invalid';
                            break;
                    }
                    if ($tag) {
                        $cell = $table->addCell($colWidth, $cellStyle);
                        $tr = $cell->addTextRun([
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                        ]);
                        $tr->addText($this->safe($columnValue), $fontStyle, $centerAlign);
                        $tr->addTextBreak();
                        $tr->addText($tag, ['color' => '555', 'size' => 5, 'bgColor' => 'f2f2f2']);
                    } else {
                        $table->addCell($colWidth, $cellStyle)
                            ->addText($this->safe($columnValue), $fontStyle, $centerAlign);
                    }
                }
            }
        }
    }

    private function safe($text)
    {
        if (!$text) return '-';
        // Remove HTML tags
        $text = strip_tags($text);
        // Fix encoding issues
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        // Escape XML (CRITICAL)
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
