<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\Domain;
use Filament\Widgets\ChartWidget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AssessmentDomainDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'توزيع المعايير حسب المجالات';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get current assessment
        $assessment = Assessment::latest()->first();

        if (!$assessment) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $domains = Domain::with('criteria')->get();

        $availableByDomain = [];
        $unavailableByDomain = [];
        $domainLabels = [];

        foreach ($domains as $domain) {
            $domainLabels[] = $domain->name;

            $criteriaIds = $domain->criteria->pluck('id')->toArray();

            $availableCount = $assessment->items()
                ->whereIn('criteria_id', $criteriaIds)
                ->where('is_available', true)
                ->count();

            $unavailableCount = $assessment->items()
                ->whereIn('criteria_id', $criteriaIds)
                ->where('is_available', false)
                ->count();

            $availableByDomain[] = $availableCount;
            $unavailableByDomain[] = $unavailableCount;
        }

        return [
            'datasets' => [
                [
                    'label' => __('نعم'),
                    'data' => $availableByDomain,
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => __('لا'),
                    'data' => $unavailableByDomain,
                    'backgroundColor' => '#EF4444',
                ],
            ],
            'labels' => $domainLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
