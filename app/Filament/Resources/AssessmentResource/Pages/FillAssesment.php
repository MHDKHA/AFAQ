<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Filament\Resources\AssessmentResource\Widgets\DomainSelectorWidget;
use App\Models\Assessment;
use App\Models\AssesmentItem;
use App\Models\Category;
use App\Models\Criterion;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Concerns\InteractsWithForms;

class FillAssesment extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AssessmentResource::class;
    protected static string $view = 'filament.resources.assessment-resource.pages.fill-assessment';

    public Assessment $record;
    public array $formResponses = [];
    public ?int $selectedDomain = null;

    // Show footer widget selector
    protected bool $hasFooterWidgets = true;

    public function mount(): void
    {
        $this->selectedDomain = null;
        $this->loadAssessmentData();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label(__('assessment.fill.back_to_assessments'))
                ->url(AssessmentResource::getUrl())
                ->color('secondary'),
        ];
    }

    public function loadAssessmentData(): void
    {
        $locale = App::getLocale();


        $query = Criterion::with(['category', 'domain'])
            ->orderBy('order');

        if ($this->selectedDomain) {
            $query->whereHas('category', fn($q) => $q->where('domain_id', $this->selectedDomain));
        }

        $criteria = $query->get();

        $this->formResponses = [];
        foreach ($criteria as $criterion) {
            $item = AssesmentItem::firstOrCreate(
                [
                    'assesment_id' => $this->record->id,
                    'criteria_id'   => $criterion->id,
                ],
                ['is_available' => false, 'notes' => '']
            );

            $this->formResponses[$criterion->id] = [
                'is_available' => $item->is_available,
                'notes'        => $item->notes,
            ];
        }

        $this->form->fill($this->formResponses);
    }

    public function form(Form $form): Form
    {
        return $form->schema([])->statePath('formResponses');
    }

    public function save(): void
    {
        DB::transaction(function () {
            foreach ($this->formResponses as $critId => $resp) {
                AssesmentItem::updateOrCreate(
                    ['assesment_id' => $this->record->id, 'criteria_id' => $critId],
                    ['is_available' => $resp['is_available'] ?? false, 'notes' => $resp['notes'] ?? '']
                );
            }
        });

        Notification::make()
            ->title(__('assessment.fill.save_success'))
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Forms\Components\Actions\Action::make('save')
                ->label(__('assessment.fill.save'))
                ->submit('save'),
        ];
    }

    public function handleDomainSelected($domainId): void
    {
        $this->selectedDomain = $domainId ?: null;
        $this->loadAssessmentData();
    }

    protected $listeners = ['domain-selected' => 'handleDomainSelected'];

    protected function getViewData(): array
    {
        $locale = App::getLocale();

        // 1. Grab all the domain IDs for this tool in one go
        $domainIds = $this->record->tool->domains->pluck('id')->toArray();


        // 2. Fetch every Criterion whose category belongs to any of those domains
        $criteria = Criterion::with(['category', 'domain'])
            ->whereHas('category', fn($q) => $q->whereIn('domain_id', $domainIds))
            ->orderBy('order')
            ->get();

        // 3. Group by category name (localized)
        $grouped = $criteria->groupBy(function ($c) use ($locale) {
            return $locale === 'ar'
                ? $c->category->name_ar
                : $c->category->name;
        });

        // 4. Merge into the parent view data
        return array_merge(parent::getViewData(), [
            'criteria' => $grouped,
        ]);
    }

//    protected function getFooterWidgets(): array
//    {
//        return [
//            DomainSelectorWidget::class,
//        ];
//    }

    public function getFooterWidgetsColumns(): int
    {
        return 1;
    }

    protected function getWidgetsData(): array
    {
        return [
            'selectedDomain' => $this->selectedDomain,
            'tool' => $this->record->tool,
        ];
    }
}
