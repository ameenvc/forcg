<?php

namespace App\Helpers\Complaint;

use App\Complaint;
use App\ClientDetail;
use App\Exports\Complaint\ComplaintExcelExport;
use App\Exports\Complaint\ComplaintWordExport;
use Mpdf\Mpdf;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\File;

class ComplaintExportHelper
{
    public static function exportData($ids, $headers, $tab, $format)
    {
        $clientDetails = ClientDetail::first();
        $data = Complaint::with(
            'products',
            'complaintMeta',
            'customerDetails',
            'department',
            'responsiblePerson',
            'raisedByPerson',
            'recievedByPerson',
            'plantDetails',
            'complaintCategory',
            'vendor',
            'division',
            'subDivision',
            'teamLead',
            'reviewedBy',
            'closedBy')
            ->whereIn('id', $ids)->orderBy('id', 'desc')->get()
            ->map(function ($complaint) {
                $metaArray = $complaint->complaintMeta
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                foreach ($metaArray as $key => $value) {
                    $complaint->{$key} = $value;
                }
                unset($complaint->complaintMeta);
                return $complaint;
            });

        switch ($format) {
            case 'pdf':
                return self::generatePdf($clientDetails, $data, $headers, $tab);
                break;
            case 'excel':
                return self::generateExcel($clientDetails, $data, $headers, $tab);
                break;
            case 'word':
                return self::generateWord($clientDetails, $data, $headers, $tab);
                break;
            default:
                # code...
                break;
        }
    }

    private static function generatePdf($clientDetails, $data, $headers, $tab)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $name = $tab.'_complaint.pdf';
        ini_set('pcre.backtrack_limit', '50000000');
        ini_set('pcre.recursion_limit', '50000000');
        $tempPath = storage_path('app/mpdf');
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }

        $orientation = count($headers) < 9 ? 'P' : 'L';    // orientation based on no of columns
        $paperSize = count($headers) > 20 ? 'a1' : (count($headers) > 13 ? 'a2' : (count($headers) > 10 ? 'a3' : 'a4')); // paper size
        $mpdf = new Mpdf([
            'orientation' => $orientation,
            'format'      => $paperSize,
            'tempDir'     => $tempPath,
            'setAutoTopMargin' => 'stretch'
        ]);

        $htmlHeading = view('exporting.forcomplaintmanagementnewheading', compact('clientDetails'));
        $html = view('exporting.forcomplaintmanagementnew', compact('clientDetails', 'data', 'headers', 'tab'))->render();
        $mpdf->SetFooter('{PAGENO} of {nbpg}');
        if($clientDetails && $clientDetails->client_logo){
            $mpdf->SetWatermarkImage(public_path('storage/clientlogos/'.$clientDetails->client_logo), 0.1);
        }
        $mpdf->showWatermarkImage = true;
        $mpdf->SetHTMLHeader($htmlHeading);
        $mpdf->WriteHTML($html);
        return $mpdf->Output($name, 'D');

    }

    private static function generateExcel($clientDetails, $data, $headers, $tab)
    {
        if (ob_get_length()) ob_end_clean();
        return Excel::download(
            new ComplaintExcelExport($clientDetails, $data, $headers, $tab),
            $tab.'_complaint.xlsx'
        );
    }

    private static function generateWord($clientDetails, $data, $headers, $tab)
    {
        if (ob_get_length()) ob_end_clean();
        $export = new ComplaintWordExport($clientDetails, $data, $headers, $tab);
        $filePath = $export->generate();
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

}
