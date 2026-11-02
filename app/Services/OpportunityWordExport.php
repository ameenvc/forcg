<?php

namespace App\Services;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Auth;

class OpportunityWordExport
{
    protected $commanData;
    private $usableWidth = 15000;

    public function __construct($commanData)
    {
        $this->commanData = $commanData;
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

        $filePath = storage_path('Opportunity Datas savedBy_'.Auth::user()->name.'.docx');
            


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

        $fixedWidth = 6200;
        $dynamicColumns = 5;
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

        $table->addCell(600, $cellStyle)
            ->addText('S.No.', $headerFont, $centerAlign);

        $table->addCell(1500, $cellStyle)
            ->addText('ID', $headerFont, $centerAlign);

        $table->addCell(2000, $cellStyle)
            ->addText('Department', $headerFont, $centerAlign);
        
        $table->addCell(2000, $cellStyle)
            ->addText('Responsibility', $headerFont, $centerAlign);

        $table->addCell(2500, $cellStyle)
            ->addText('Opportunity Name', $headerFont, $centerAlign);

        $table->addCell(1000, $cellStyle)
            ->addText('Target Date', $headerFont, $centerAlign);

        $table->addCell(2500, $cellStyle)
            ->addText('Type', $headerFont, $centerAlign);

        $table->addCell(1000, $cellStyle)
            ->addText('Added Date', $headerFont, $centerAlign);

        $table->addCell(1000, $cellStyle)
            ->addText('Positive Impact', $headerFont, $centerAlign);

        $table->addCell(1500, $cellStyle)
            ->addText('Process Stage', $headerFont, $centerAlign);

        $table->addCell(1000, $cellStyle)
            ->addText('Action Status', $headerFont, $centerAlign);
        // =========================
        //  DATA ROWS
        // =========================
        foreach ($this->commanData['datas'] as $i => $issue) {
            $department = $issue->dept ? $issue->dept->departmentname : '-';
            $responsibility = $issue->byresponsibility ? $issue->byresponsibility->name.' - '.$issue->byresponsibility->employeeid : '-';
            $result = [];
            $today = date('Y-m-d');
            if($issue->typeRelation && count($issue->typeRelation)){
                foreach($issue->typeRelation as $typeRelation){
                    $result[] = $typeRelation->name;
                }
            }
            $result = count($result)>0 ? implode(', ',$result) : '-';  
            $targetDate = date('d-m-Y', strtotime($issue->tardate));
            $addedDate = date('d-m-Y', strtotime($issue->createdat));
            if($issue->actionstatus=='Progress-25%')
                $actionStatus = '25%';
            elseif($issue->actionstatus=='Partially Completed-50%')
                $actionStatus = '50%';
            elseif($issue->actionstatus=='Completed-100%')
                $actionStatus = '100%';
            elseif($issue->actionstatus=='Not Started-0%')
                $actionStatus = '0%';
            else 
                $actionStatus = '0%';
            $table->addRow(null, [
                'cantSplit' => true
            ]);
            
            //  BASIC FIELDS
            $table->addCell(500, $cellStyle)
                ->addText($this->safe($i + 1), $fontStyle, $centerAlign);

            $table->addCell(1000, $cellStyle)
                ->addText($this->safe($issue->dep_id), $fontStyle, $centerAlign);

            $table->addCell(2000, $cellStyle)
                ->addText($this->safe($department), $fontStyle, $centerAlign);
            $table->addCell(2000, $cellStyle)
                ->addText($this->safe($responsibility), $fontStyle, $centerAlign);

            $table->addCell(2500, $cellStyle)
                ->addText($this->safe($issue->oppname), $fontStyle, $centerAlign);

            //Color for target date
            // Create Cell
            $cell = $table->addCell(1000, [
                'valign' => 'center'
            ]);
            $textRun = $cell->addTextRun($centerAlign);
            //default style for date
            $tardateFontStyle = [
                'size'    => 7,
                'bold'    => true,
                'color'   => '176044',
                'bgColor' => 'e0edcf'
            ];
            if ($issue->tardate <= $today) {
                $tardateFontStyle['color'] = 'af1e1e';
                $tardateFontStyle['bgColor'] = 'f8d4d4';
            } 
            // Add badge-like stage text
            $textRun->addText(
                ' ' . $this->safe($targetDate) . ' ',
                $tardateFontStyle
            );
            $table->addCell(2500, $cellStyle)
                ->addText($this->safe($result), $fontStyle, $centerAlign);

            $table->addCell(1000, $cellStyle)
                ->addText($this->safe($addedDate), $fontStyle, $centerAlign);
            //Color for rank
            // Create Cell
            $cell = $table->addCell(1000, [
                'valign' => 'center'
            ]);
            $textRun = $cell->addTextRun($centerAlign);
            //default style for impact
            $rankFontStyle = [
                'size'    => 7,
                'bold'    => true,
                'color'   => '176044',
                'bgColor' => 'e0edcf'
            ];
            if ($issue->impact == "High") {

                $rankFontStyle['color'] = 'af1e1e';
                $rankFontStyle['bgColor'] = 'f8d4d4';

            } elseif (
               $issue->impact == "Moderate"
            ) {

                $rankFontStyle['color'] = 'cc8800';
                $rankFontStyle['bgColor'] = 'ffebcc';

            } 
            // Add badge-like stage text
            $textRun->addText(
                ' ' . $this->safe($issue->impact) . ' ',
                $rankFontStyle
            );
            // =========================
            // PROCESS STAGE
            // =========================

            $stageText = $issue->status;

            // Create Cell
            $cell = $table->addCell(1500, [
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
            if (
                $stageText == 'Review Pending' 
            ) {

                $stageFontStyle['color'] = 'af6f1e';
                $stageFontStyle['bgColor'] = 'fbdece';

            } elseif ($stageText == 'Approved') {
                $stageFontStyle['color'] = '176044';
                $stageFontStyle['bgColor'] = 'e0edcf';

            } elseif ($stageText == 'Disapproved') {

                $stageFontStyle['color'] = 'af1e1e';
                $stageFontStyle['bgColor'] = 'f8d4d4';

            } elseif (
                $stageText == 'Completed'
            ) {

                $stageFontStyle['color'] = '25499c';
                $stageFontStyle['bgColor'] = 'd9e2f6';

            } else {
                $stageText = '-';
                $stageFontStyle['color'] = '000000';
            }

            // Add badge-like stage text
            $textRun->addText(
                ' ' . $this->safe($issue->status) . ' ',
                $stageFontStyle
            );

            $table->addCell(1000, $cellStyle)
                ->addText($this->safe($actionStatus), $fontStyle, $centerAlign);
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