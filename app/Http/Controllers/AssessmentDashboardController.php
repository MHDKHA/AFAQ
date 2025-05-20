<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssesmentItem;
use App\Models\Criterion;
use App\Models\Domain;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\AssessmentReportMail;

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
        // Make sure the user can access this assessment
        $user = Auth::user();
        if ($assessment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to assessment');
        }

        // Get locale
        $locale = $request->get('locale', 'ar');
        App::setLocale($locale);

        // Get domains with their categories and criteria specifically for this tool
        $tool = $assessment->tool;
        $domains = $tool ? $tool->domains()->with(['categories.criteria'])->orderBy('order')->get() :
            Domain::with(['categories.criteria'])->orderBy('order')->get();

        // Get all assessment items for this assessment
        $assessmentItems = AssesmentItem::where('assessment_id', $assessment->id)->get();

        // Calculate statistics
        $totalItems = $assessmentItems->count();
        $availableItems = $assessmentItems->where('is_available', true)->count();
        $unavailableItems = $assessmentItems->where('is_available', false)->count();

        $availableRate = $totalItems > 0 ? round(($availableItems / $totalItems) * 100) : 0;
        $unavailableRate = $totalItems > 0 ? round(($unavailableItems / $totalItems) * 100) : 0;

        // Total expected criteria count
        $totalExpectedItems = Criterion::whereHas('category.domain', function($query) use ($domains) {
            $query->whereIn('domains.id', $domains->pluck('id'));
        })->count();

        $completionRate = $totalExpectedItems > 0 ? round(($totalItems / $totalExpectedItems) * 100) : 0;

        // Gather statistics
        $statistics = [
            'totalItems' => $totalItems,
            'availableItems' => $availableItems,
            'unavailableItems' => $unavailableItems,
            'availableRate' => $availableRate,
            'unavailableRate' => $unavailableRate,
            'completionRate' => $completionRate,
            'totalExpectedItems' => $totalExpectedItems,
        ];

        // Calculate domain-specific statistics for charts
        $domainStats = [];
        foreach ($domains as $domain) {
            $criteriaIds = $domain->criteria()->pluck('id')->toArray();

            $domainItems = $assessmentItems->whereIn('criteria_id', $criteriaIds);
            $domainAvailable = $domainItems->where('is_available', true)->count();
            $domainUnavailable = $domainItems->where('is_available', false)->count();
            $domainTotal = $domainItems->count();

            $domainStats[$domain->name] = [
                'available' => $domainAvailable,
                'unavailable' => $domainUnavailable,
                'total' => $domainTotal,
                'availableRate' => $domainTotal > 0 ? round(($domainAvailable / $domainTotal) * 100) : 0,
            ];
        }

        // Return Inertia response with the data needed for the dashboard
        return Inertia::render('Assessments/Dashboard', [
            'assessment' => $assessment->load('tool'),
            'domains' => $domains,
            'statistics' => $statistics,
            'domainStats' => $domainStats,
            'locale' => $locale,
            'canGenerateReport' => $user->can('generate_reports') && $completionRate >= 50, // Only allow report generation if the assessment is at least 50% complete
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
        // Verify ownership
        $user = Auth::user();
        if ($assessment->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to assessment'
            ], 403);
        }

        $request->validate([
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $locale = $request->input('locale', 'ar');
        App::setLocale($locale);

        try {
            // Generate PDF path
            $pdfPath = route('assessment-reports.export-pdf', [
                'record' => $assessment->id,
                'locale' => $locale
            ]);

            // Default subject if none provided
            $subject = $request->input('subject') ?:
                ($locale === 'ar' ? 'تقرير التقييم: ' . ($assessment->name_ar ?: $assessment->name) :
                    'Assessment Report: ' . $assessment->name);

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

    /**
     * Save assessment items.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request, $id)
    {
        try {
            $assessment = Assessment::findOrFail($id);

            // Check ownership if user is authenticated
            if (Auth::check() && $assessment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to edit this assessment'
                ], 403);
            }

            // Validate the incoming request
            $validated = $request->validate([
                'responses' => 'required|array',
            ]);

            // Save each assessment item
            foreach ($validated['responses'] as $criterionId => $response) {
                AssesmentItem::updateOrCreate(
                    [
                        'assessment_id' => $assessment->id,
                        'criteria_id' => $criterionId
                    ],
                    [
                        'is_available' => $response['is_available'] ?? false,
                        'notes' => $response['notes'] ?? ''
                    ]
                );
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Assessment saved successfully',
                'assessment_id' => $assessment->id
            ]);
        } catch (\Exception $e) {
            // Return error response with details
            return response()->json([
                'success' => false,
                'message' => 'Error saving assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
