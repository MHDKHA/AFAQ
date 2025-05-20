<?php

// app/Http/Controllers/ToolController.php
namespace App\Http\Controllers;

use App\Models\AssesmentItem;
use App\Models\Assessment;
use App\Models\Tool;
use App\Models\TemporaryAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ToolController extends Controller
{
    /**
     * Display list of available tools
     */
    public function index()
    {
        $user = Auth::user();

        // Get all active tools
        $query = Tool::where('is_active', true);

        // If user is logged in, filter by their roles
        if ($user) {
            $userRoles = $user->roles->pluck('name')->toArray();
            $query->where(function($q) use ($userRoles) {
                $q->whereNull('role_name')
                    ->orWhereIn('role_name', $userRoles);
            });
        } else {
            // If not logged in, only show tools without role requirement
            $query->whereNull('role_name');
        }

        $tools = $query->get();

        return Inertia::render('Tools/Index', [
            'tools' => $tools,
            'userRoles' => $user ? $user->roles->pluck('name') : [],
        ]);
    }

    /**
     * Show tool and its assessment form
     */
    public function show(Request $request, $slug)
    {
        $tool = Tool::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $user = Auth::user();

        // Check if the user has access to this tool
        if ($tool->role_name && (!$user || !$user->hasRole($tool->role_name))) {
            return redirect()->route('tools.index')
                ->with('error', 'You do not have permission to access this tool');
        }

        // Get domains, categories, and criteria for this tool
        $domains = $tool->domains()
            ->with(['categories' => function ($query) {
                $query->orderBy('order');
            }, 'categories.criteria' => function ($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Load existing assessment if any
        $existingAssessment = null;

        if ($user) {
            $existingAssessment = $user->assessments()
                ->where('tool_id', $tool->id)
                ->with('items')
                ->latest()
                ->first();
        } else {
            // For anonymous users, look for temporary assessment in session
            $sessionId = $request->session()->getId();
            $existingAssessment = TemporaryAssessment::where('session_id', $sessionId)
                ->where('tool_id', $tool->id)
                ->latest()
                ->first();
        }

        return Inertia::render('Tools/Show', [
            'tool' => $tool,
            'domains' => $domains,
            'existingAssessment' => $existingAssessment,
            'isAuthenticated' => !!$user,
        ]);
    }

    /**
     * Store assessment responses
     */
    public function storeAssessment(Request $request, $slug)
    {
        $tool = Tool::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'responses' => 'required|array',
            'email' => 'nullable|email',
            'name' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user) {
            // Create or update an assessment for authenticated user
            $assessment = Assessment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'tool_id' => $tool->id,
                ],
                [
                    'name' => $tool->name . ' - ' . now()->toDateString(),
                    'name_ar' => $tool->name_ar ? $tool->name_ar . ' - ' . now()->toDateString() : null,
                    'date' => now(),
                    'description' => 'Assessment for ' . $tool->name,
                ]
            );

            // Process and save individual assessment items
            foreach ($validated['responses'] as $criterionId => $response) {
                AssesmentItem::updateOrCreate(
                    [
                        'assessment_id' => $assessment->id,
                        'criteria_id' => $criterionId,
                    ],
                    [
                        'is_available' => $response['is_available'] ?? false,
                        'notes' => $response['notes'] ?? '',
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Assessment saved successfully',
                'assessment_id' => $assessment->id,
                'dashboard_url' => route('assessment-dashboard.show', $assessment->id),
            ]);
        } else {
            // Create or update a temporary assessment for non-authenticated users
            $sessionId = $request->session()->getId();

            $tempAssessment = TemporaryAssessment::updateOrCreate(
                [
                    'session_id' => $sessionId,
                    'tool_id' => $tool->id,
                ],
                [
                    'ip_address' => $request->ip(),
                    'email' => $validated['email'] ?? null,
                    'name' => $validated['name'] ?? null,
                    'responses' => $validated['responses'],
                    'completed_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Your assessment has been saved. Create an account to see your dashboard and reports.',
                'temp_assessment_id' => $tempAssessment->id,
            ]);
        }
    }

    /**
     * Generate a report for a tool assessment
     */
    public function report(Request $request, $slug, $assessmentId)
    {
        $tool = Tool::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $assessment = Assessment::where('id', $assessmentId)
            ->where('user_id', $user->id)
            ->where('tool_id', $tool->id)
            ->firstOrFail();

        // Redirect to the assessment dashboard
        return redirect()->route('assessment-dashboard.show', $assessment->id);
    }
}
