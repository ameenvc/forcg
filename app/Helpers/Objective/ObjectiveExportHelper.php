<?php
namespace App\Helpers\Objective;

use App\ClientDetail;
use App\Exports\Objective\ObjectiveWordExport;
use App\Exports\Objective\ObjectiveExcelExport;
use App\Objective;
use Mpdf\Mpdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class ObjectiveExportHelper
{
    public static function exportData($ids, $headers, $format, $contextData)
    {
        $clientDetails = ClientDetail::first();
        $data = Objective::with('objectiveTypes', 'dep','division','area','approverDetails','creator','approvedBy')->whereIn('Oid', $ids)->orderBy('Oid', 'desc')->get();

        switch ($format) {
            case 'pdf':
                return self::generatePdf($clientDetails, $data, $headers, $contextData);
                break;
            case 'excel':
                return self::generateExcel($clientDetails, $data, $headers, $contextData);
                break;
            case 'word':
                return self::generateWord($clientDetails, $data, $headers, $contextData);
                break;

            default:
                # code...
                break;
        }
    }

    private static function generatePdf($clientDetails, $data, $headers, $contextData)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $name = 'objectives.pdf';
        ini_set('pcre.backtrack_limit', '50000000');
        ini_set('pcre.recursion_limit', '50000000');
        $tempPath = storage_path('app/mpdf');
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }

        // $orientation = count($headers) < 9 ? 'P' : 'L';    // orientation based on no of columns
        // $paperSize = count($headers) > 20 ? 'a1' : (count($headers) > 13 ? 'a2' : (count($headers) > 10 ? 'a3' : 'a4')); // paper size
        $orientation = 'P';
        $paperSize = 'a4';

        $mpdf = new Mpdf([
            'orientation' => $orientation,
            'format'      => $paperSize,
            'tempDir'     => $tempPath,
            'setAutoTopMargin' => 'stretch'
        ]);

        $htmlHeading = view('exporting.exportPdfHeading', compact('clientDetails'));
        $html = view('exporting.forObjectiveNew', compact('clientDetails', 'data', 'headers', 'contextData'))->render();
        $mpdf->SetFooter('{PAGENO} of {nbpg}');
        if($clientDetails && $clientDetails->client_logo){
            $mpdf->SetWatermarkImage(public_path('storage/clientlogos/'.$clientDetails->client_logo), 0.1);
        }
        $mpdf->showWatermarkImage = true;
        $mpdf->SetHTMLHeader($htmlHeading);
        $mpdf->WriteHTML($html);
        return $mpdf->Output($name, 'D');
    }

    private static function generateExcel($clientDetails, $data, $headers, $contextData)
    {
        if (ob_get_length()) ob_end_clean();
        return Excel::download(
            new ObjectiveExcelExport($clientDetails, $data, $headers, $contextData),
            'objective.xlsx'
        );
    }

    private static function generateWord($clientDetails, $data, $headers, $contextData)
    {
        if (ob_get_length()) ob_end_clean();
        $export = new ObjectiveWordExport($clientDetails, $data, $headers, $contextData);
        $filePath = $export->generate();
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
