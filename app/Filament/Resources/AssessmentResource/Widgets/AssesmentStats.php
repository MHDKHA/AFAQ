<?php

namespace App\Filament\Resources\AssessmentResource\Widgets;

use App\Models\Assesment;
use App\Models\Criterion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssesmentStats extends BaseWidget
{

    public ?Assesment $record = null;
    protected function getStats(): array
    {
        // If for some reason $record isn't injected yet, return nothing
        if (! $this->record) {
            return [];
        }

        $assessment = $this->record;
        $totalItems       = $assessment->items()->count();
        $availableItems   = $assessment->items()->where('is_available', true)->count();
        $unavailableItems = $totalItems - $availableItems;

        $availableRate  = $totalItems > 0 ? round(($availableItems / $totalItems) * 100) : 0;
        $completionRate = $totalItems > 0 ? round(($totalItems      / $availableItems)          * 100) : 0;

        return [
            Stat::make(__('assessment.stats.assessment_name'),   $assessment->name)
                ->description(__('assessment.stats.assessment_date', ['date' => $assessment->date->format('Y-m-d')]))
                ->color('primary'),

            Stat::make(__('assessment.stats.available_items'), $availableItems)
                ->description(__(':rate% of assessed items', ['rate' => $availableRate]))
                ->color('success')
                ->chart([0, 0, $availableRate, $availableRate]),

            Stat::make(__('assessment.stats.unavailable_items'), $unavailableItems)
                ->description(__(':rate% of assessed items', ['rate' => (100 - $availableRate)]))
                ->color('danger')
                ->chart([0, 0, 100 - $availableRate, 100 - $availableRate]),

            Stat::make(__('assessment.stats.assessment_completion'), "{$totalItems} / " . Criterion::count())
                ->description(__(':rate% completed', ['rate' => $completionRate]))
                ->color('info')
                ->chart([0, 0, $completionRate, $completionRate]),
        ];
    }
}
