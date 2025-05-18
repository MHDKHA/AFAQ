<?php



namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssesmentItem;
use App\Models\Category;
use App\Models\Domain;
use Illuminate\Http\Request;

class AssesmentController extends Controller
{
    public function print(Assessment $assessment)
    {

        $labels = [
            'التحسين',
            'تقييم الأداء',
            'التشغيل',
            'الدعم',
            'التخطيط',
            'القيادة',
            'التأسيس الإداري',
        ];

// 2. manually define your three data sets
        $available    = [1, 0, 3, 0, 0, 3, 1];  // متوفر
        $notAvailable = [6, 6, 10, 5, 3, 4, 7]; // غير متوفر
        $notApplied   = [0, 0, 0, 0, 0, 0, 0];  // لا ينطبق

        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label'           => 'متوفر',
                        'data'            => $available,
                        'backgroundColor' => 'Blue',  // blue-ish
                    ],
                    [
                        'label'           => 'غير متوفر',
                        'data'            => $notAvailable,
                        'backgroundColor' => 'red',  // red-ish
                    ],
                    [
                        'label'           => 'لا ينطبق',
                        'data'            => $notApplied,
                        'backgroundColor' => ' gray',  // green-ish
                    ],
                ],
            ],
            'options' => [
                'indexAxis' => 'x',
                'plugins'   => [
                    'legend' => [
                        'position' => 'bottom',
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks'       => [
                            'stepSize' => 1,
                        ],
                    ],
                ],
            ],
        ];

// 3. build the QuickChart URL
        $quickChartUrl = 'https://quickchart.io/chart?width=800&height=400&c='
            . urlencode(json_encode($chartConfig));

        // 1) Load domains with their categories → criteria (ordered)
        $domains = Domain::with(['categories.criteria' => function ($query) {
            $query->orderBy('order');
        }])
            ->orderBy('order')
            ->get();

        // 2) Load all assessment items for this assessment
        $assessmentItems = AssesmentItem::where('assesment_id', $assessment->id)->get();

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
            'quickChartUrl'         => $quickChartUrl,
        ]);
    }
}
