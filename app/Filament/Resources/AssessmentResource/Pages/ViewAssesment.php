<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Models\Assesment;
use App\Models\Assessment;
use App\Models\Criterion;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\App;

class ViewAssesment extends Page
{
    protected static string $resource = AssessmentResource::class;
    protected static string $view     = 'filament.resources.assessment-resource.pages.view-assessment';

    // Declare this so Blade can see it
    public Assessment $record;

    protected bool $hasHeaderWidgets = true;
    protected bool $hasFooterWidgets = true;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('assessment.view.back_to_assessments'))
                ->url(AssessmentResource::getUrl())
                ->color('secondary'),

            Action::make('print')
                ->label(__('assessment.view.print_report'))
                ->url(route('assessment.print', $this->record))
                ->icon('heroicon-o-printer')
                ->openUrlInNewTab()
                ->color('success'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\AssessmentResource\Widgets\AssesmentStats::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Resources\AssessmentResource\Widgets\AssesmentResults::class,
        ];
    }

    public function getWidgetsData(): array
    {
        return [
            'record'         => $this->record,
        ];
    }

    protected function getViewData(): array
    {
        $locale = session('locale', 'ar');
        App::setLocale($locale);
        session(['locale' => $locale]);
        $criteria = Criterion::with('category')
            ->orderBy('order')
            ->get()
            ->groupBy(function($c)use($locale) {
                if($locale === 'ar') {
                return  $c->category->name_ar;
                }
               return $c->category->name;
            });



        return array_merge(parent::getViewData(), [
            'criteria' => $criteria,
        ]);
    }
}
