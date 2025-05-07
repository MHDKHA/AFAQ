<?php

namespace App\Http\Controllers;

use App\Models\AssessmentReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class ReportController extends Controller
{
    public function export(AssessmentReport $report)
    {
        $pdf = PDF::loadView('reports.assessment-report', [
            'report' => $report
        ]);

        return $pdf->download('assessment-report-' . $report->company_name . '.pdf');
    }
}
