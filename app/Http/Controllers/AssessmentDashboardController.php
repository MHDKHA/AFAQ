<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssesmentItem;
use App\Models\Criterion;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\AssessmentReportMail;
use Illuminate\Support\Facades\App;

class AssessmentDashboardController extends Controller
{
    /**
     * Display the assessment dashboard.
     *
     * @param  Assessment  $assessment
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function show(Assessment $assessment, Request $request)
    {
        $locale = $request->get('locale', 'ar');
        App::setLocale($locale);

        // Get domains with their categories and criteria
        $domains = Domain::with(['categories.criteria'])->orderBy('order')->get();

        // Get all assessment items for this assessment
        $assessmentItems = AssesmentItem::where('assessment_id', $assessment->id)->get();

        // Calculate statistics
        $totalItems = $assessmentItems->count();
        $availableItems = $assessmentItems->where('is_available', true)->count();
        $unavailableItems = $assessmentItems->where('is_available', false)->count();

        $availableRate = $totalItems > 0 ? round(($availableItems / $totalItems) * 100) : 0;
        $unavailableRate = $totalItems > 0 ? round(($unavailableItems / $totalItems) * 100) : 0;

        // Total expected criteria count (39 as shown in the frontend code)
        $completionRate = round(($totalItems / 39) * 100);

        $statistics = [
            'totalItems' => $totalItems,
            'availableItems' => $availableItems,
            'unavailableItems' => $unavailableItems,
            'availableRate' => $availableRate,
            'unavailableRate' => $unavailableRate,
            'completionRate' => $completionRate,
        ];

        // Return Inertia response with the data needed for the dashboard
        return Inertia::render('Assessments/Dashboard', [
            'assessment' => $assessment,
            'domains' => $domains,
            'statistics' => $statistics,
            'locale' => $locale,
        ]);
    }

    /**
     * Send assessment report via email.
     *
     * @param  Assessment  $assessment
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReport(Assessment $assessment, Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $locale = $request->input('locale', 'ar');
        App::setLocale($locale);

        try {
            // Generate PDF or get its path
            $pdfPath = route('assessment-reports.export-pdf', [
                'record' => $assessment->id,
                'locale' => $locale
            ]);

            // Default subject if none provided
            $subject = $request->input('subject') ?:
                ($locale === 'ar' ? 'تقرير التقييم: ' . $assessment->name_ar : 'Assessment Report: ' . $assessment->name);

            // Send email with PDF attachment
            Mail::to($request->input('email'))
                ->send(new AssessmentReportMail(
                    $assessment,
                    $subject,
                    $request->input('message'),
                    $pdfPath,
                    $locale
                ));

            return response()->json([
                'success' => true,
                'message' => $locale === 'ar' ? 'تم إرسال التقرير بنجاح' : 'Report sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $locale === 'ar' ? 'حدث خطأ أثناء إرسال التقرير' : 'Error sending the report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
