<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\Category;
use App\Models\Criterion;
use Filament\Widgets\ChartWidget;

class AssessmentRadarWidget extends ChartWidget
{
    protected static ?string $heading = 'مستوى تقييم المعايير';
    protected int|string|array $columnSpan = 'full';

    // Add a property to track the current assessment ID
    public ?int $assessmentId = null;

    // Make the widget poll-able if you need real-time updates
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        // Get categories for criteria
        $categories = Category::with('criteria')->get();
        $categoryNames = $categories->pluck('name')->toArray();

        // Get the assessment - use the latest one if no specific ID is provided
        $assessment = $this->assessmentId
            ? Assessment::find($this->assessmentId)
            : Assessment::latest()->first();

        if (!$assessment) {
            // Return empty data if no assessment exists
            return [
                'datasets' => [
                    [
                        'label' => __('مستوى التقييم'),
                        'data' => array_fill(0, count($categoryNames), 0),
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'borderColor' => 'rgb(59, 130, 246)',
                        'pointBackgroundColor' => 'rgb(59, 130, 246)',
                        'pointBorderColor' => '#fff',
                        'pointHoverBackgroundColor' => '#fff',
                        'pointHoverBorderColor' => 'rgb(59, 130, 246)',
                    ],
                ],
                'labels' => $categoryNames,
            ];
        }

        $categoryScores = [];

        foreach ($categories as $category) {
            $categoryTotal = 0;
            $availableCriteria = 0;

            foreach ($category->criteria as $criterion) {
                // Get assessment item for this criterion
                $assessmentItem = $assessment->items()
                    ->where('criteria_id', $criterion->id)
                    ->first();

                // If assessment item exists and is available, increment score
                if ($assessmentItem && $assessmentItem->is_available) {
                    $categoryTotal++;
                }

                $availableCriteria++;
            }

            // Calculate score on 0-3 scale
            $score = $availableCriteria > 0
                ? round(($categoryTotal / $availableCriteria) * 3, 1)
                : 0;

            $categoryScores[] = $score;
        }

        return [
            'datasets' => [
                [
                    'label' => __('مستوى التقييم'),
                    'data' => $categoryScores,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $categoryNames,
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'r' => [
                    'min' => 0,
                    'max' => 3,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                    'pointLabels' => [
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => __('top'),
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
