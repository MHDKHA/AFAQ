<?php

namespace App\Http\Controllers;

use App\Models\AssessmentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class AssessmentResultsController extends Controller
{
    public function exportPdf(AssessmentReport $record, Request $request)
    {



        // 5. Render the appropriate Blade
        $view = 'product-survey-blade';  // your Arabic view
        $html = view($view)->render();

        // 6. Send to mPDF
        $mpdf = $this->initMpdf();
        $mpdf->WriteHTML($html);

        $filename = "assessment-report-.pdf";
        $output   = $mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN);

        return response($output, 200, ['Content-Type' => 'application/pdf']);
    }

    private function initMpdf(): Mpdf
    {
        $configVars = (new ConfigVariables())->getDefaults();
        $fontVars = (new FontVariables())->getDefaults();

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'calibri',
        ]);
    }
}
