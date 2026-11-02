<?php

namespace App\Services;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class CorrectiveWordExport
{
    protected $commanData,$tab;
    private $usableWidth = 15000;

    public function __construct($commanData,$tab)
    {
        $this->commanData = $commanData;
        $this->tab = $tab;
    }

    public function generate()
    {
        $phpWord = new PhpWord();
        $orientation = 'landscape';
        $pageSizeW = 11906;
        $pageSizeH = 16838;
        if($this->commanData['paper']=='a1'){
            $pageSizeH = 29100;
            $pageSizeW = 32600;
        }elseif($this->commanData['paper']=='a2'){
            $pageSizeW = 32100;
            $pageSizeH = 23811;
        }elseif($this->commanData['paper']=='a3'){
            $pageSizeH = 16838;
            $pageSizeW = 23811;
        }elseif($this->commanData['orientation']=='L' && $this->commanData['paper']=='a4'){
            $pageSizeW = 16838;
            $pageSizeH = 11906;
        }else{
            $orientation = 'portrait';
        }
        $marginLeft = 200;
        $marginRight = 200;
        $this->usableWidth = $pageSizeW - $marginLeft - $marginRight;
        $section = $phpWord->addSection([
            'orientation' => $orientation,
            'pageSizeW' => $pageSizeW,
            'pageSizeH' => $pageSizeH,
            'marginTop'    => 300,
            'marginBottom' => 300,
            'marginLeft'   => $marginLeft,
            'marginRight'  => $marginRight,
        ]);
        $this->addHeader($section);
        $this->addTable($section);  

        $filePath = storage_path($this->tab.'_ncr_and_corrective_actions.docx');

        IOFactory::createWriter($phpWord, 'Word2007')->save($filePath);

        return $filePath;
    }

    private function addHeader($section)
    {
        $header = $section->addHeader();
        $table = $header->addTable([
            'width' => 100 * 50,
            'unit' => 'pct',
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
        ]);

        $table->addRow();

        // LEFT → LOGO
        $cell1 = $table->addCell(2000);

        $logoPath = public_path('storage/clientlogos/'.$this->commanData['clientDetails']->client_logo);

        if ($this->commanData['clientDetails']->client_logo && file_exists($logoPath)) {
            $cell1->addImage($logoPath, [
                'width' => 80,
                'height' => 40,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT
            ]);
        }

        // CENTER → COMPANY
        $cell2 = $table->addCell(6000);
        $cell2->addText(
            $this->commanData['clientDetails']->company_name ?? '',
            ['bold' => true, 'size' => 14],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        // RIGHT → DATE
        $cell3 = $table->addCell(2000);
        $cell3->addText(
            'Exported: '.date('d-m-Y'),
            ['size' => 10],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
        );

        // $section->addTextBreak(1);
    }

    private function addTable($section)
    {
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
            'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'layout'      => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
            'width'       => 5000, // 100%
            'unit'        => 'pct',
        ]);
        $baseWidth = 10000;

        $slnoWidth   = 500;
        $idWidth     = 1000;
        $titleWidth  = 2000;
        $processWidth = 1200;
        $statusWidth  = 900;

        $fixedWidth = 6200;
        $dynamicColumns = count(
            $this->commanData['tableDetails'][$this->tab]['columns_active']
        );
        $dynamicWidth = max(
            600, intval(($baseWidth - $fixedWidth) / max(1, $dynamicColumns))
        );

        //  Styles
        $fontStyle = ['size' => 7];
        $headerFont = ['bold' => true, 'size' => 7];
        $centerAlign = ['alignment' => 'center'];
        $cellStyle = ['valign' => 'center'];

        // =========================
        //  HEADER ROW
        // =========================
        $table->addRow(null, ['tblHeader' => true]);

        $table->addCell($slnoWidth, $cellStyle)
            ->addText('Slno', $headerFont, $centerAlign);

        $table->addCell($idWidth, $cellStyle)
            ->addText('Id', $headerFont, $centerAlign);

        $table->addCell($titleWidth, $cellStyle)
            ->addText('Title', $headerFont, $centerAlign);

        foreach ($this->commanData['tableDetails'][$this->tab]['columns_active'] as $col) {
            $table->addCell($dynamicWidth, $cellStyle)
                ->addText(
                    ucfirst($col->display_label ?? $col->column_name),
                    $headerFont,
                    $centerAlign
                );
        }

        $table->addCell($processWidth, $cellStyle)
            ->addText('Process Stage', $headerFont, $centerAlign);

        $table->addCell($statusWidth, $cellStyle)
            ->addText('Action Status', $headerFont, $centerAlign);

        // =========================
        //  DATA ROWS
        // =========================
        foreach ($this->commanData[$this->tab] as $i => $issue) {
            $responsible_names = isset($this->commanData['userForResponsibility'])
                ? $this->commanData['userForResponsibility']
                    ->whereIn('email', explode(',', $issue->correction_responsibility))
                    ->pluck('name')
                    ->toArray()
                : [];
            $responsible_names = implode(',', $responsible_names);
            $cat = $issue->cat ? $issue->cat->categoryname : '';
            $investigation_team = [];
            foreach($issue->investigators as $investigator) {
                $investigation_team[] = $investigator->email;
            }
            $productFieldValues = $issue->ncrFieldValueRelation->keyBy('field_name');
            $processFieldValues = $issue->processNcrFieldValueRelation->keyBy('field_name');
            $vendorFieldValues = $issue->vendorNcrFieldValueRelation->keyBy('field_name');
            $table->addRow(null, [
                'cantSplit' => true
            ]);
            
            //  BASIC FIELDS
            $table->addCell(500, $cellStyle)
                ->addText($this->safe($i + 1), $fontStyle, $centerAlign);

            $table->addCell($dynamicWidth, $cellStyle)
                ->addText($this->safe($issue->issueid), $fontStyle, $centerAlign);

            $table->addCell($dynamicWidth, $cellStyle)
                ->addText($this->safe($issue->title), $fontStyle, $centerAlign);

            //  DYNAMIC COLUMNS
            foreach ($this->commanData['tableDetails'][$this->tab]['columns_active'] as $value) {
                $fontStyle = ['size' => 7,'color'=>'000000'];
                if($value->category_name && $value->category_name=='product ncr'){
                    $fieldData = $productFieldValues[$value->field_name] ?? null;
                    $fieldValue = $fieldData->value ?? null;
                    $fieldDisplayLabel = $fieldData->display_label ?? null;
                }else if($value->category_name && $value->category_name=='process ncr'){
                    $fieldData = $processFieldValues[$value->field_name] ?? null;
                    $fieldValue = $fieldData->value ?? null;
                    $fieldDisplayLabel = $fieldData->display_label ?? null;
                }else if($value->category_name && $value->category_name=='vendor ncr'){
                    $fieldData = $vendorFieldValues[$value->field_name] ?? null;
                    $fieldValue = $fieldData->value ?? null;
                    $fieldDisplayLabel = $fieldData->display_label ?? null;
                }
                $text = '-';

                switch ($value->column_name) {

                    case 'category':
                        $text = $cat;
                        $categoryFontStyle = [
                            'size'  => 7,
                            'color' => '000000'
                        ];
                        if ($issue->category == "Management Review") {
                            $categoryFontStyle['color'] = '33CC33';
                            $text = $issue->category;
                        } elseif ($issue->category == "Legal Requirement") {
                            $categoryFontStyle['color'] = 'FF0066';
                            $text = $issue->category;
                        } elseif ($issue->category == "Incident Investigation") {
                            $categoryFontStyle['color'] = 'FFA500';
                            $text = $issue->category;
                        } elseif ($cat == "Corrective Action") {
                            $categoryFontStyle['color'] = '000000';
                        }elseif ($cat == "Management Review") {
                            $categoryFontStyle['color'] = '33CC33';
                            $text = $cat;
                        } else {
                            $categoryFontStyle['color'] = '000000';
                        }
                        $table->addCell($dynamicWidth, $cellStyle)
                            ->addText(
                                $this->safe($text),
                                $categoryFontStyle,
                                $centerAlign
                            );
                    continue 2;
                    case 'type':
                        $text = $issue->typ ? $issue->typ->name : '-';
                        // Create Cell
                        $cell = $table->addCell($dynamicWidth, [
                            'valign' => 'center'
                        ]);
                        // Text Run for centered badge-like text
                        $textRun = $cell->addTextRun($centerAlign);
                        // Default styles
                        $typeFontStyle = [
                            'size'    => 7,
                            'bold'    => true,
                            'color'   => '25499C',
                            'bgColor' => 'D9E2F6'
                        ];
                        // Dynamic colors from DB
                        if ($issue->typ) {
                            $bgColor = str_replace('#', '', $issue->typ->background_color);
                            $txtColor = str_replace('#', '', $issue->typ->text_color);
                            $typeFontStyle['color'] = $txtColor;
                            $typeFontStyle['bgColor'] = $bgColor;
                        }
                        // Add badge-like text
                        $textRun->addText(
                            ' ' . $this->safe($text) . ' ',
                            $typeFontStyle
                        );
                    continue 2;
                    case 'department':
                        $text = $issue->correctionDept ? $issue->correctionDept->departmentname : '-';
                        break;

                    case 'division':
                        $text = $issue->division ? $issue->division->name : '-';
                        break;

                    case 'subdivision':
                        $text = $issue->area ? $issue->area->name : '-';
                        break;

                    case 'raisedby':
                        $text = $issue->postedByCorrective ? $issue->postedByCorrective->name : '-';
                        break;
                    case 'financial loss' :
                        $text = $issue->financial_loss ? $issue->financial_loss : '-';
                        break;
                    case 'potential cost saving' :
                        $text = $issue->potential_cost_savings ?? '-';
                        break;
                    case 'responsibility':
                        $text = $responsible_names ?  $responsible_names : '-';
                        break;
                    case 'email remainder for action':
                        $text = $issue->frequency ?? '-';
                        break;
                    case 'due date':
                        $text = $issue->duedate
                            ? date('d-m-Y', strtotime($issue->duedate))
                            : '-';
                        break;
                    case 'investigation team lead' :
                        $text = $issue->teamLead ? $issue->teamLead->name : '-';
                        break;
                    case 'investigation target date' :
                        $text = $issue->investigation_date ? date('d-m-Y',strtotime($issue->investigation_date)) :'-';
                        break;
                    case 'correction target date':
                        $text = $issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) :'-';
                        break;
                    case 'source' :
                       $text = $issue->source ?? '-';
                       break;
                    case  'added date':
                        $text = $issue->created_at ? date('d-m-Y',strtotime($issue->created_at)) : date('d-m-Y',strtotime($issue->postdate));
                        break;
                    case 'action reviewed by':
                        $text = $issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->name : '-';
                        break;
                    case 'action reviewed date':
                        $text = $issue->action_reviewed_at ? date('d-m-Y',strtotime($issue->action_reviewed_at)) : '-';
                        break;
                    case 'closure reviewed by':
                        $text = $issue->closedByCorrective ? $issue->closedByCorrective->name : '-';
                        break;
                    case 'closure reviewed date':
                        $text = $issue->closed ? date('d-m-Y',strtotime($issue->closed)) : '-';
                        break;
                    case 'detection stage':
                        $text = $issue->categoryDetectionStage ? $issue->categoryDetectionStage->name : '-';
                        break;
                    case 'job order number':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {
                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );
                            // Line Break
                            $textRun->addTextBreak(1);
                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }
                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'product serial number':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'product name':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'product code':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'batch':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'production line':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'date of manufacture':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'quantity affected':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'process name':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'machine / equipment id':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'operator / shift':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'date of process':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'vendor name':
                        $text = $fieldValue && isset($commanData['vendors'][$fieldValue]) ? $commanData['vendors'][$fieldValue] : '-';
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($text),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'item serial number':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'purchase order (PO) number':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'receiving report (RR) number':
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($fieldValue),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                    case 'delivery date':
                        $text =  $fieldValue ? date('d-m-Y', strtotime($fieldValue)) : '-';
                        $cell = $table->addCell($dynamicWidth, $cellStyle);
                        $textRun = $cell->addTextRun($centerAlign);
                        if ($fieldValue) {

                            // Main Value
                            $textRun->addText(
                                $this->safe($text),
                                [
                                    'size' => 7,
                                    'color' => '000000'
                                ]
                            );

                            // Line Break
                            $textRun->addTextBreak(1);

                            // Display Label Badge
                            if (
                                $fieldDisplayLabel &&
                                $fieldDisplayLabel != $value->display_label
                            ) {

                                $textRun->addText(
                                    ' ' . $this->safe($fieldDisplayLabel) . ' ',
                                    [
                                        'size'    => 6,
                                        'color'   => '555555',
                                        'bgColor' => 'F2F2F2'
                                    ]
                                );
                            }

                        } else {
                            $textRun->addText(
                                '-',
                                [
                                    'size' => 7
                                ]
                            );
                        }
                    continue 2;
                }

                $table->addCell($dynamicWidth, $cellStyle)
                    ->addText($this->safe($text), $fontStyle, $centerAlign);
            }
            
            // =========================
            // PROCESS STAGE
            // =========================

            $stageText = $issue->processstage;

            // Create Cell
            $cell = $table->addCell($dynamicWidth, [
                'valign' => 'center'
            ]);

            // TextRun for centered badge-like text
            $textRun = $cell->addTextRun($centerAlign);

            // Default style
            $stageFontStyle = [
                'size'    => 7,
                'bold'    => true,
                'color'   => '000000',
                'bgColor' => 'FFFFFF'
            ];

            // =========================
            // PROCESS STAGE COLORS
            // =========================

            if ($stageText == 'Awaiting actions') {

                $stageFontStyle['color'] = '737373';
                $stageFontStyle['bgColor'] = 'E6E6E6';

            } elseif (
                $stageText == 'Correction Proposed- RC Awaiting' ||
                $stageText == 'Actions Proposed- Review Awaiting' ||
                $stageText == 'RC Updated- Correction Awaiting'
            ) {

                $stageFontStyle['color'] = '25499C';
                $stageFontStyle['bgColor'] = 'D9E2F6';

            } elseif ($stageText == 'Action Reviewed, OK') {

                $stageText = 'Action Approved';

                $stageFontStyle['color'] = '176044';
                $stageFontStyle['bgColor'] = 'E0EDCF';

            } elseif ($stageText == 'Action Reviewed, Not OK') {

                $stageText = 'Action Rejected';

                $stageFontStyle['color'] = 'AF1E1E';
                $stageFontStyle['bgColor'] = 'F8D4D4';

            } elseif ($stageText == 'rejected') {

                $stageText = 'Entry Rejected';

                $stageFontStyle['color'] = 'AF1E1E';
                $stageFontStyle['bgColor'] = 'F8D4D4';

            } elseif (
                $stageText == 'Need More Action' ||
                $stageText == 'Entry Under Review'
            ) {

                $stageFontStyle['color'] = 'AF6F1E';
                $stageFontStyle['bgColor'] = 'FBDECE';

            } elseif ($stageText == 'Actions Completed') {

                $stageFontStyle['color'] = '25499C';
                $stageFontStyle['bgColor'] = 'D9E2F6';
            }

            // =========================
            // CLOSED STATUS OVERRIDE
            // =========================

            else if (
                $issue->status == 'closed' &&
                $issue->closed <= $issue->correction_target_date
            ) {

                $stageText = $issue->status;

                $stageFontStyle['color'] = '176044';
                $stageFontStyle['bgColor'] = 'E0EDCF';

            } elseif ($issue->status == 'closed after due') {

                $stageText = $issue->status;

                $stageFontStyle['color'] = 'AF6F1E';
                $stageFontStyle['bgColor'] = 'FBDECE';
            } else {
                $stageText = '-';
                $stageFontStyle['color'] = '000000';
            }

            // Add badge-like stage text
            $textRun->addText(
                ' ' . $this->safe($stageText) . ' ',
                $stageFontStyle
            );
            //  ACTION %
            $table->addCell(900, $cellStyle)
                ->addText($this->safe($issue->average_percent . '%'), $fontStyle, $centerAlign);
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