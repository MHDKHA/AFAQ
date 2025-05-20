<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssesmentItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function fill(Assessment $assessment)
    {
        // Authorization check
        if ($assessment->user_id !== auth()->id()) {
            abort(403);
        }

        // Load the tool with its domains, categories, and criteria
        $assessment->load(['tool.domains.categories.criteria', 'items']);

        return Inertia::render('Assessments/Fill', [
            'assessment' => $assessment,
            'domains' => $assessment->tool->domains,
            'locale' => app()->getLocale()
        ]);
    }

    public function save(Request $request, Assessment $assessment)
    {
        // Authorization check
        if ($assessment->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'array',
            'responses.*.is_available' => 'boolean',
            'responses.*.notes' => 'nullable|string'
        ]);

        foreach ($validated['responses'] as $criteriaId => $response) {
            AssesmentItem::updateOrCreate(
                [
                    'assessment_id' => $assessment->id,
                    'criteria_id' => $criteriaId
                ],
                [
                    'is_available' => $response['is_available'] ?? false,
                    'notes' => $response['notes'] ?? ''
                ]
            );
        }

        // Check if the assessment is complete enough to show subscription prompt
        $totalCriteria = $assessment->tool->domains()
            ->withCount('criteria')
            ->get()
            ->sum('criteria_count');

        $answeredCriteria = $assessment->items()->count();
        $completionRate = $totalCriteria > 0 ? ($answeredCriteria / $totalCriteria) * 100 : 0;

        $showSubscriptionPrompt = $completionRate >= 50; // Show subscription prompt at 50% completion

        if ($showSubscriptionPrompt) {
            return redirect()->route('dashboard')->with('subscription_prompt', true);
        }

        return redirect()->route('assessment.fill', $assessment)
            ->with('success', 'Your progress has been saved successfully.');
    }
}
