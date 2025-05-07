<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StandardChart extends ApexChartWidget
{
    protected static ?string $chartId = 'standardChart';
    protected static ?string $heading = 'معايير التقييم';

    // 1) Tell Filament: use the full width of the page
    protected static ?string $maxWidth   = 'full';
    protected array|string|int $columnSpan = 2;

    protected function getOptions(): array
    {
        $categories = Category::withCount('criteria')
            ->orderBy('order')
            ->get();

        $names  = $categories->pluck('name')->toArray();
        $counts = $categories->pluck('criteria_count')->toArray();

        $height = max(300, count($names) * 40);

        return [
            'chart' => [
                'type'   => 'bar',
                'height' => 300,
                // 2) Make the chart itself stretch
                'width'  => '100%',
            ],
            'series' => [
                [
                    'name' => 'عدد الأسئلة',
                    'data' => $counts,
                ],
            ],
            'xaxis' => [
                'categories' => $names,
                'labels'     => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal'   => false,
                ],
            ],
        ];
    }
}
