<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Tool;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get user's assessments with tool and items
        $assessments = $user->assesments()
            ->with(['tool', 'items'])
            ->latest()
            ->get();

        // Add computed properties to each assessment
        foreach ($assessments as $assessment) {
            $assessment->available_count = $assessment->items->where('is_available', true)->count();
            $assessment->total_count = $assessment->items->count();

            // Get total criteria count for this tool
            $totalCriteria = $assessment->tool ? $assessment->tool->getTotalCriteriaCount() : 0;

            $assessment->completion_percentage = $totalCriteria > 0
                ? round(($assessment->total_count / $totalCriteria) * 100)
                : 0;

            // Check if user has full access
            $assessment->has_full_access = $user->hasRole('premium') || $user->hasRole('admin');
        }

        $showSubscriptionPrompt = $request->session()->get('subscription_prompt', false);

        return Inertia::render('Dashboard', [
            'assessments' => $assessments,
            'showSubscriptionPrompt' => $showSubscriptionPrompt,
            'hasSubscription' => $user->hasRole('premium'),
            'locale' => app()->getLocale(),
            'tools' => Tool::where('is_active', true)->get()
        ]);
    }
}
