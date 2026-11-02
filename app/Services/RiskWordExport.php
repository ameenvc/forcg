<?php

namespace App\Services;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class RiskWordExport
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

        $filePath = storage_path('Enterprise Risk Management Datas.docx');

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

        $table->addCell($slnoWidth, $cellStyle)
            ->addText('S.No.', $headerFont, $centerAlign);

        $table->addCell($idWidth, $cellStyle)
            ->addText('Risk ID', $headerFont, $centerAlign);

        $table->addCell($titleWidth, $cellStyle)
            ->addText('Risk Group', $headerFont, $centerAlign);
        
        $table->addCell($idWidth, $cellStyle)
            ->addText('Risk Area', $headerFont, $centerAlign);

        $table->addCell($titleWidth, $cellStyle)
            ->addText('Risk Name', $headerFont, $centerAlign);

        $table->addCell($idWidth, $cellStyle)
            ->addText('Risk', $headerFont, $centerAlign);

        $table->addCell($titleWidth, $cellStyle)
            ->addText('Risk Owner', $headerFont, $centerAlign);

        $table->addCell($titleWidth, $cellStyle)
            ->addText('Risk Response', $headerFont, $centerAlign);

        $table->addCell($processWidth, $cellStyle)
            ->addText('Process Stage', $headerFont, $centerAlign);

        // =========================
        //  DATA ROWS
        // =========================
        foreach ($this->commanData['datas'] as $i => $issue) {
            $department = $issue->department ? $issue->department->departmentname : '-';
            if ($issue->risk_owner_users->isNotEmpty()) {
                $owners = [];
                foreach ($issue->risk_owner_users as $user) {
                    $owners[] = $user->name . ' - ' . $user->employeeid;
                }
                $result = implode(",", $owners);
            } else {
                $result = '-';
            }

            $table->addRow(null, [
                'cantSplit' => true
            ]);
            
            //  BASIC FIELDS
            $table->addCell(500, $cellStyle)
                ->addText($this->safe($i + 1), $fontStyle, $centerAlign);

            $table->addCell(1000, $cellStyle)
                ->addText($this->safe($issue->grp_id), $fontStyle, $centerAlign);

            $table->addCell(1500, $cellStyle)
                ->addText($this->safe($issue->riskgroup), $fontStyle, $centerAlign);

            $table->addCell(2000, $cellStyle)
                ->addText($this->safe($department), $fontStyle, $centerAlign);

            $table->addCell(2500, $cellStyle)
                ->addText($this->safe($issue->riskname), $fontStyle, $centerAlign);
            //Color for rank
            // Create Cell
            $cell = $table->addCell(2500, [
                'valign' => 'center'
            ]);
            $textRun = $cell->addTextRun($centerAlign);
            //default style for rank
            $rankFontStyle = [
                'size'    => 7,
                'bold'    => true,
                'color'   => '176044',
                'bgColor' => 'e0edcf'
            ];
            if ($issue->rank == "High") {

                $rankFontStyle['color'] = 'af1e1e';
                $rankFontStyle['bgColor'] = 'f8d4d4';

            } elseif (
               $issue->rank == "Moderate"
            ) {

                $rankFontStyle['color'] = 'cc8800';
                $rankFontStyle['bgColor'] = 'ffebcc';

            } 
            // Add badge-like stage text
            $textRun->addText(
                ' ' . $this->safe($issue->rank) . ' ',
                $rankFontStyle
            );

            $table->addCell(2000, $cellStyle)
                ->addText($this->safe($result), $fontStyle, $centerAlign);
            
            $table->addCell(900, $cellStyle)
                ->addText($this->safe($issue->risk_response), $fontStyle, $centerAlign);
            // =========================
            // PROCESS STAGE
            // =========================

            $stageText = $issue->action_status;

            // Create Cell
            $cell = $table->addCell(2500, [
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
            if ($stageText == 'Active Risk') {

                $stageFontStyle['color'] = '176044';
                $stageFontStyle['bgColor'] = 'e0edcf';

            } elseif (
                $stageText == 'Under review' ||
                $stageText == 'Awaiting Periodic Review'
            ) {

                $stageFontStyle['color'] = 'af6f1e';
                $stageFontStyle['bgColor'] = 'fbdece';

            } elseif ($stageText == 'Action under review') {
                $stageFontStyle['color'] = '000000';
                $stageFontStyle['bgColor'] = 'e6e6e6';

            } elseif ($stageText == 'Rejected') {

                $stageFontStyle['color'] = 'af1e1e';
                $stageFontStyle['bgColor'] = 'f8d4d4';

            } elseif (
                $stageText == 'Completed'
            ) {

                $stageFontStyle['color'] = '25499c';
                $stageFontStyle['bgColor'] = 'd9e2f6';

            } elseif ($stageText == 'Closed') {

                $stageFontStyle['color'] = '000000';
                $stageFontStyle['bgColor'] = 'fbdece';
            } else {
                $stageText = '-';
                $stageFontStyle['color'] = '000000';
            }

            // Add badge-like stage text
            $textRun->addText(
                ' ' . $this->safe($issue->action_status) . ' ',
                $stageFontStyle
            );
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