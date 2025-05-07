<?php



namespace App\Http\Controllers;

use App\Models\Assesment;
use App\Models\AssesmentItem;
use App\Models\Category;
use App\Models\Domain;
use Illuminate\Http\Request;

class AssesmentController extends Controller
{
    public function print(Assesment $assessment)
    {
        // 1) Load domains with their categories → criteria (ordered)
        $domains = Domain::with(['categories.criteria' => function ($query) {
            $query->orderBy('order');
        }])
            ->orderBy('order')
            ->get();

        // 2) Load all assessment items for this assessment
        $assessmentItems = AssesmentItem::where('assessment_id', $assessment->id)->get();

        // 3) Compute totals and percentages
        $totalItems       = $assessmentItems->count();
        $availableItems   = $assessmentItems->where('is_available', true)->count();
        $unavailableItems = $assessmentItems->where('is_available', false)->count();

        // Avoid divide‑by‑zero; adjust denominator (39) as needed
        $completionRate   = 39 > 0
            ? round(($totalItems / 39) * 100)
            : 0;
        $availableRate    = $totalItems > 0
            ? round(($availableItems / $totalItems) * 100)
            : 0;
        $unavailableRate  = $totalItems > 0
            ? round(($unavailableItems / $totalItems) * 100)
            : 0;

        // ───────────────────────────────────────────────────────
        // 4) Build our bar‑chart via QuickChart.io
        $categories = Category::withCount('criteria')
            ->orderBy('order')
            ->get();

        $names  = $categories->pluck('name')->toArray();
        $counts = $categories->pluck('criteria_count')->toArray();

        $chartConfig = [
            'type'    => 'bar',
            'data'    => [
                'labels'   => $names,
                'datasets' => [[
                    'label' => 'عدد الأسئلة',
                    'data'  => $counts,
                ]],
            ],
            'options' => [
                'indexAxis' => 'x',      // vertical bars
                'plugins'   => [
                    'legend' => ['display' => false],
                ],
            ],
        ];

        $chartUrl = 'https://quickchart.io/chart?width=800&height=400&c='
            . urlencode(json_encode($chartConfig));
        // ───────────────────────────────────────────────────────

        // 5) Render the PDF‑view (or normal view) with all data
        return view('assessments.print', [
            'assessment'       => $assessment,
            'domains'          => $domains,
            'assessmentItems'  => $assessmentItems,
            'totalItems'       => $totalItems,
            'availableItems'   => $availableItems,
            'unavailableItems' => $unavailableItems,
            'completionRate'   => $completionRate,
            'availableRate'    => $availableRate,
            'unavailableRate'  => $unavailableRate,
            'chartUrl'         => $chartUrl,
        ]);
    }
}
