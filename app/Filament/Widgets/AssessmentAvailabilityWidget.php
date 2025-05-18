<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\AssesmentItem;
use App\Models\Category;
use App\Models\Criterion;
use App\Models\Domain;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssessmentAvailabilityWidget extends ChartWidget
{
    protected static ?string $heading = 'معايير الجاهزية';
//    protected int|string|array $columnSpan = '';

    protected function getData(): array
    {
        // Get current assessment (you might need to adjust this based on your actual implementation)
        $assessment = Assessment::latest()->first() ?? [];

        if (!$assessment) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $availableCount = $assessment->getAvailableCountAttribute();
        $unavailableCount = $assessment->getUnavailableCountAttribute();

        return [
            'datasets' => [
                [
                    'label' => __('حالة توفر المعايير'),
                    'data' => [$availableCount, $unavailableCount],
                    'backgroundColor' => ['#10B981', '#EF4444'],
                ],
            ],
            'labels' => [__('نعم'), __('لا')],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}




// This is how you would implement Apexcharts specifically
// Note: You'll need to install the flowframe/laravel-trend package:
// composer require flowframe/laravel-trend

class ApexChartsImplementation
{
    public static function criteriaAvailabilityChart($assessment)
    {
        $availableCount = $assessment->getAvailableCountAttribute();
        $unavailableCount = $assessment->getUnavailableCountAttribute();

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
                'animations' => [
                    'enabled' => true,
                ],
            ],
            'series' => [$availableCount, $unavailableCount],
            'labels' => [__('نعم'), __('لا')],
            'legend' => [
                'position' => 'bottom',
            ],
            'colors' => ['#10B981', '#EF4444'],
            'responsive' => [
                [
                    'breakpoint' => 480,
                    'options' => [
                        'chart' => [
                            'width' => 200
                        ],
                        'legend' => [
                            'position' => 'bottom'
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function domainDistributionChart($assessment)
    {
        $domains = Domain::all();
        $domainNames = $domains->pluck('name')->toArray();
        $availableCounts = [];
        $unavailableCounts = [];

        foreach ($domains as $domain) {
            $criteriaIds = Criterion::where('domain_id', $domain->id)->pluck('id')->toArray();

            $availableCounts[] = AssesmentItem::where('assessment_id', $assessment->id)
                ->whereIn('criteria_id', $criteriaIds)
                ->where('is_available', true)
                ->count();

            $unavailableCounts[] = AssesmentItem::where('assessment_id', $assessment->id)
                ->whereIn('criteria_id', $criteriaIds)
                ->where('is_available', false)
                ->count();
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
                'stacked' => true,
                'toolbar' => [
                    'show' => false
                ],
                'animations' => [
                    'enabled' => true,
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '55%',
                ],
            ],
            'dataLabels' => [
                'enabled' => false
            ],
            'stroke' => [
                'show' => true,
                'width' => 2,
                'colors' => ['transparent']
            ],
            'xaxis' => [
                'categories' => $domainNames,
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('عدد المعايير')
                ]
            ],
            'fill' => [
                'opacity' => 1
            ],
            'colors' => ['#10B981', '#EF4444'],
            'legend' => [
                'position' => 'bottom',
            ],
            'series' => [
                [
                    'name' => __('نعم'),
                    'data' => $availableCounts
                ],
                [
                    'name' => __('لا'),
                    'data' => $unavailableCounts
                ]
            ],
        ];
    }

    public static function criteriaRadarChart()
    {
        // Get the list of criteria categories
        $categories = Category::all();
        $categoryNames = $categories->pluck('name')->toArray();

        // In a real implementation, you would calculate the actual scores
        // This is placeholder data based on your PDF
        $scores = array_fill(0, count($categoryNames), 3);

        return [
            'chart' => [
                'height' => 350,
                'type' => 'radar',
                'toolbar' => [
                    'show' => false,
                ],
                'animations' => [
                    'enabled' => true,
                ],
            ],
            'series' => [
                [
                    'name' => __('مستوى التقييم'),
                    'data' => $scores,
                ]
            ],
            'labels' => $categoryNames,
            'plotOptions' => [
                'radar' => [
                    'size' => 140,
                    'polygons' => [
                        'strokeColors' => '#e9e9e9',
                        'fill' => [
                            'colors' => ['#f8f8f8', '#fff']
                        ]
                    ]
                ]
            ],
            'colors' => ['#3B82F6'],
            'markers' => [
                'size' => 4,
                'colors' => ['#3B82F6'],
                'strokeWidth' => 2,
            ],
            'tooltip' => [
                'y' => [
                    'formatter' => 'function(val) { return val }'
                ]
            ],
            'yaxis' => [
                'tickAmount' => 3,
                'min' => 0,
                'max' => 3,
            ]
        ];
    }
}
