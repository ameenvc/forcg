<?php

namespace App\Exports\Objective;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Helpers\Complaint\ComplaintHelper;

class ObjectiveWordExport {

    public function __construct($clientDetails, $data, $headers, $contextData) {
        $this->clientDetails = $clientDetails;
        $this->data = $data;
        $this->headers = $headers;
        $this->contextData = $contextData;
    }

    public function generate(){
        $phpWord = new PhpWord();
        $orientation = 'portrait';
        $paperSize = 'a4';

        $pageSizeW = 11906;
        $pageSizeH = 16838;

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
        $this->addContextData($section, $pageSizeW, $paperSize);
        $this->addTable($section, $pageSizeW);

        $filePath = storage_path('objectives.docx');
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
            ['bold' => true, 'size' => 14, 'color' => '000000'],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        // Export Date (Right aligned)
        $cell3 = $table->addCell(intval($usedWidth*0.25));
        $cell3->addText(
            'Exported: '.date('d-m-Y'),
            ['size' => 10, 'color' => '000000'],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]
        );

    }

    private function addContextData($section, $pageSizeW, $paperSize)
    {
        $department = $this->contextData['department'];
        if ($department) {
            $textRun = $section->addTextRun();

            $textRun->addText('Department: ');
            $textRun->addText($department, [
                'bold' => true,
            ]);
        }

        $division = $this->contextData['division'];
        if ($division) {
            $textRun = $section->addTextRun();

            $textRun->addText('Division: ');
            $textRun->addText($division, [
                'bold' => true,
            ]);
        }

        $subDivision = $this->contextData['subDivision'];
        if ($subDivision) {
            $textRun = $section->addTextRun();

            $textRun->addText('Sub Division: ');
            $textRun->addText($subDivision, [
                'bold' => true,
            ]);
        }
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
        $headerFont = ['bold' => true, 'size' => 8];
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
                    ucfirst($col),
                    $headerFont,
                    $centerAlign
                );
        }

        // =========================
        //  DATA ROWS
        // =========================
        foreach ($this->data as $i => $d) {
            $table->addRow(null, [
                'cantSplit' => true
            ]);

            [$fColor, $bgColor] = getProcessStageStyle($d->status, 'word');
            $specialFontStyle = ['size' => 7, 'color' => $fColor, 'bgColor' => $bgColor];

            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($i+1), $fontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->objective_id), $specialFontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->objectiveTypes ? $d->objectiveTypes->pluck('name')->implode(', ') : ''), $fontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->objectives ?? ''), $fontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->otarget ?? ''), $fontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->oachieved ?? ''), $fontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->tardate ?? ''), $fontStyle, $centerAlign);
            $responsibleUsers = getUsersFromEmailsOrNames($d->responsibility);
            $responsibleUsersName = $responsibleUsers->pluck('name')->implode(', ');
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($responsibleUsersName), $fontStyle, $centerAlign);
            $table->addCell($colWidth, $cellStyle)
                ->addText($this->safe($d->status), $specialFontStyle, $centerAlign);
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
