<?php

namespace App\Http\Controllers;

use App\Models\AssesmentItem;
use App\Models\Assessment;
use App\Models\Category;
use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendAssessmentController extends Controller
{
    public function saveAssessment(Request $request, UserRegistration $registration)
    {
        $validated = $request->validate([
            'formResponses' => 'required|array',
        ]);

        // Create a new assessment for this user registration
        $assessment = Assessment::create([
            'name' => 'Web Assessment for ' . $registration->name,
            'name_ar' => 'تقييم الويب لـ ' . $registration->name,
            'date' => now(),
            'description' => 'Assessment submitted via web form',
            // If you want to associate it with a user, you could create one or use a default user
            'user_id' => 1, // Default user ID or create a new user
            'company_id' => 1, // Default company ID or create a new company based on registration
            'registration_id' => $registration->id, // Link to the user registration
        ]);

        // Save each assessment item
        foreach ($validated['formResponses'] as $criterionId => $response) {
            AssesmentItem::create([
                'assessment_id' => $assessment->id,
                'criteria_id' => $criterionId,
                'is_available' => $response['is_available'] ?? false,
                'notes' => $response['notes'] ?? '',
            ]);
        }

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Assessment saved successfully',
            'assessment_id' => $assessment->id,
        ]);
    }

    public function getAssessmentForm(UserRegistration $registration)
    {
        // Get all criteria grouped by category
        $criteria = Category::with(['criteria' => function ($query) {
            $query->orderBy('order');
        }])
            ->get()
            ->groupBy('name')
            ->toArray();

        return Inertia::render('Registration/Assessment', [
            'registration' => $registration,
            'criteria' => $criteria,
        ]);
    }
}
