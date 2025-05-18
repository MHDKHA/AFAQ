<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Database\Eloquent\Collection;

class AssessmentOverviewWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'assessment_dashboard';
    protected static ?string $heading = 'Assessment Overview';

    // Display on the Assessment resource pages
    public static function canView(): bool
    {
        // matches routes defined in AssessmentResource (filament.afaq.resources.assessments.*)
        return request()->routeIs('filament.afaq.resources.assessments.*');
    }

    protected function getOptions(): array
    {
        $assessment = Assessment::find($this->getRecordId());
        if (! $assessment) {
            return [];
        }

        // 1. Availability counts
        $availableCount = $assessment->available_count;
        $unavailableCount = $assessment->unavailable_count;

        // 2. Domain distribution
        $domainCounts = $assessment->items()
            ->with('criterion.domain')
            ->get()
            ->groupBy(fn($item) => $item->criterion->domain->name)
            ->map(fn(Collection $col) => $col->count())
            ->toArray();

        // 3. Category breakdown
        $categoryCounts = $assessment->items()
            ->with('criterion.category')
            ->get()
            ->groupBy(fn($item) => $item->criterion->category->name)
            ->map(fn(Collection $col) => $col->count())
            ->toArray();

        return [
            'series' => [
                [
                    'name' => __('assessment.chart.availability'),
                    'data' => [$availableCount, $unavailableCount],
                ],
                [
                    'name' => __('assessment.chart.domains'),
                    'data' => array_values($domainCounts),
                ],
                [
                    'name' => __('assessment.chart.categories'),
                    'data' => array_values($categoryCounts),
                ],
            ],
            'chart' => [
                'height' => 350,
                'type' => 'bar',
                'toolbar' => ['show' => true],
            ],
            'plotOptions' => [
                'bar' => ['horizontal' => false],
            ],
            'xaxis' => [
                'categories' => array_merge(
                    [__('assessment.chart.available'), __('assessment.chart.unavailable')],
                    array_keys($domainCounts),
                    array_keys($categoryCounts)
                ),
            ],
            'legend' => ['position' => 'top'],
        ];
    }
}
