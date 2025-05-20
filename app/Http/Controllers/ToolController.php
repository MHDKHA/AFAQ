<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Tool;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::where('is_active', true)->get();

        return Inertia::render('Tools/Index', [
            'tools' => $tools,
            'locale' => app()->getLocale()
        ]);
    }

    public function show($slug)
    {
        $tool = Tool::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Store tool in session for after login/register
        session(['selected_tool' => $slug]);

        // Load domains with their categories and criteria
        $domains = $tool->domains()
            ->with(['categories' => function ($query) {
                $query->orderBy('order');
            }, 'categories.criteria' => function ($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return Inertia::render('Tools/Show', [
            'tool' => $tool,
            'domains' => $domains,
            'locale' => app()->getLocale()
        ]);
    }

    public function startAssessment(Request $request, $slug)
    {
        $tool = Tool::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Check if user already has an assessment for this tool
        $existingAssessment = $request->user()->assessments()
            ->where('tool_id', $tool->id)
            ->latest()
            ->first();

        if ($existingAssessment) {
            return redirect()->route('assessment.fill', $existingAssessment);
        }

        // Create new assessment
        $assessment = Assessment::create([
            'name' => $tool->name . ' - ' . now()->toDateString(),
            'name_ar' => $tool->name_ar . ' - ' . now()->toDateString(),
            'date' => now(),
            'description' => 'Assessment for ' . $tool->name,
            'user_id' => $request->user()->id,
            'company_id' => $request->user()->company_id ?? 1, // Default company or user's company
            'tool_id' => $tool->id
        ]);

        return redirect()->route('assessment.fill', $assessment);
    }
}
