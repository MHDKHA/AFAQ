<?php

namespace App\Filament\Resources\AssessmentResource\Widgets;

use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;

class DomainSelectorWidget extends Widget implements Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.assessment-resource.widgets.domain-selector-widget';

    // Render full width in the footer
    protected int|string|array $columnSpan = 'full';

    // Load immediately (not lazily) so the select shows on page load
    protected static bool $isLazy = false;

    // This property is populated from FillAssessment::getWidgetsData()
    public ?int $selectedDomain = null;

    // Configure the form state-binding to the $selectedDomain property
    protected function getFormModel(): string
    {
        return static::class;
    }

    public function mount(): void
    {
        // Initialize the form’s state to the page’s current domain
        $this->form->fill([
            'selectedDomain' => $this->selectedDomain,
        ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('selectedDomain')
                    ->label(__('assessment.widgets.domain'))
                    ->options(Domain::orderBy('order')->pluck('name', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(fn (int $state) => $this->emitUp('domain-selected', $state))
                    ->searchable(),
            ]);
    }

    public function getTitle(): string|Htmlable|null
    {
        return __('assessment.widgets.domain_selector_title');
    }
}
