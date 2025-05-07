<?php

namespace App\Filament\Resources\AssessmentResource\Widgets;

use App\Models\AssesmentItem;
use App\Models\Assesment;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AssesmentResults extends TableWidget
{
    // Will be injected from the parent page’s getWidgetsData()
    public ?Assesment $record = null;

    // Will be injected and updated via the domain selector widget
    public ?int $selectedDomain = null;

    protected static string $view = 'filament.resources.assessment-resource.widgets.assesment-results';
    protected int|string|array $columnSpan = 'full';

    // Listen for domain changes emitted by the selector widget
    protected $listeners = [
        'domain-selected' => 'updateDomain',
    ];

    public function updateDomain(int $domain): void
    {
        $this->selectedDomain = $domain;
    }

    /**
     * Return the base query for the table.
     */
    protected function getTableQuery(): Builder
    {
        if (! $this->record || ! $this->selectedDomain) {
            // return an empty builder
            return AssesmentItem::query()->whereRaw('0 = 1');
        }

        return AssesmentItem::query()
            ->where('assessment_id', $this->record->id)
            ->whereHas('criterion.category', function (Builder $query) {
                $query->where('domain_id', $this->selectedDomain);
            })
            ->with('criterion.category');
    }

    /**
     * Define the table columns.
     */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('criterion.order')
                ->label(__('assessment.results.order'))
                ->sortable(),

            Tables\Columns\TextColumn::make('criterion.category.name')
                ->label(__('assessment.results.main_criterion'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('criterion.question')
                ->label(__('assessment.results.audit_question'))
                ->wrap(),

            Tables\Columns\IconColumn::make('is_available')
                ->label(__('assessment.results.available'))
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),

            Tables\Columns\TextColumn::make('notes')
                ->label(__('assessment.results.notes'))
                ->limit(50),
        ];
    }

    /**
     * Define grouping of rows by category name.
     */
    protected function getTableGroups(): array
    {
        return [
            Tables\Grouping\Group::make('criterion.category.name')
                ->label(__('assessment.results.main_criterion'))
                ->collapsible(),
        ];
    }

    /**
     * Set the default group column.
     */
    protected function getDefaultTableGroup(): ?string
    {
        return 'criterion.category.name';
    }

    public function makeFilamentTranslatableContentDriver(): ?\Filament\Support\Contracts\TranslatableContentDriver
    {
        return null;
    }
}
