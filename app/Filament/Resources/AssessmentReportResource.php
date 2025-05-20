<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentReportResource\Pages;
use App\Models\AssessmentReport;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class AssessmentReportResource extends Resource
{

    protected static ?string $model = AssessmentReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Attachments')
                ->schema([
                    Forms\Components\Repeater::make('attachments')
                        ->schema([Forms\Components\TextInput::make('item')->required(),])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state) =>

                        match (true) {
                            is_string($state['item']) => $state['item'],
                            is_array($state['item']) && isset($state['item']['item']) => (string) $state['item']['item'],
                            is_array($state['item']) => json_encode($state['item']),
                            default => (string) $state['item'],
                        }
                        ),
                ]),

            Forms\Components\Section::make('Work Environment Analysis')
                ->schema([
                    ...self::environmentRepeater('pros', __('Strengths')),
                    ...self::environmentRepeater('cons', __('Weaknesses')),
                    ...self::environmentRepeater('improvement_areas', __('Improvement Areas')),
                    ...self::environmentRepeater('risk_areas', __('Risk Areas')),
                ]),

            Forms\Components\Section::make('Panel Notes')
                ->schema([
                    Forms\Components\Repeater::make('panel_notes')
                        ->schema([Forms\Components\TextInput::make('item')->required(),])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state) => match (true) {
                            is_string($state['item']) => $state['item'],
                            is_array($state['item']) && isset($state['item']['item']) => (string) $state['item']['item'],
                            is_array($state['item']) => json_encode($state['item']),
                            default => (string) $state['item'],
                        }),
                ]),

            Forms\Components\Section::make('Assessment Results')
                ->schema([
                    self::createAssessmentSection('administrative_foundation', __('Administrative Foundation')),
                    self::createAssessmentSection('leadership', __('Leadership')),
                    self::createAssessmentSection('planning', __('Planning')),
                    self::createAssessmentSection('human_resources', __('Human Resources')),
                    self::createAssessmentSection('operations', __('Operations')),
                    self::createAssessmentSection('performance_evaluation', __('Performance Evaluation')),
                    self::createAssessmentSection('improvement', __('Improvement')),
                ]),

            Forms\Components\Section::make('Expected Violations')
                ->schema([
                    Repeater::make('expected_violations')
                        ->label(__('المخالفات المتوقعة'))
                        ->reactive() // makes Livewire re-compute whenever any child changes
                        ->afterStateUpdated(function (array $state, callable $set) {
                            $sum = collect($state)->sum('fine');
                            $set('total_fine', $sum);
                        })
                        ->schema([
                            TextInput::make('violation')->label(__('المخالفة'))->required(),
                            TextInput::make('fine')->label(__('الغرامة'))->numeric()->required()->reactive(),
                            TextInput::make('solution')->label(__('الحل'))->required(),
                        ])
                        ->columns(3),
                    TextInput::make('total_fine')
                        ->label(__('إجمالي الغرامات'))
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->default(0),
                ]),

            Forms\Components\Section::make('Follow-up Services')
                ->schema([
                    Forms\Components\Repeater::make('follow_up_services')
                        ->schema([Forms\Components\TextInput::make('item')->required(),])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state) => match (true) {
                            is_string($state['item']) => $state['item'],
                            is_array($state['item']) && isset($state['item']['item']) => (string) $state['item']['item'],
                            is_array($state['item']) => json_encode($state['item']),
                            default => (string) $state['item'],
                        }),
                ]),
        ]);
    }

    protected static function environmentRepeater(string $key, string $label): array
    {
        return [
            Forms\Components\Repeater::make("work_environment.{$key}")
                ->label($label)
                ->schema([Forms\Components\TextInput::make('item')->required(),])
                ->defaultItems(0)
                ->itemLabel(fn (array $state) => match (true) {
                    is_string($state['item']) => $state['item'],
                    is_array($state['item']) && isset($state['item']['item']) => (string) $state['item']['item'],
                    is_array($state['item']) => json_encode($state['item']),
                    default => (string) $state['item'],
                }),
        ];
    }

    private static function createAssessmentSection(string $key, string $label): Forms\Components\Fieldset
    {
        return Forms\Components\Fieldset::make($label)
            ->schema([
                Forms\Components\TextInput::make("assessment_results.{$key}.achievement_percentage")
                    ->label(__('Achievement Percentage'))->numeric()->required(),
                Forms\Components\Repeater::make("assessment_results.{$key}.notes")
                    ->label(__('Notes'))->schema([Forms\Components\TextInput::make('item')->required(),])
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state) => match (true) {
                        is_string($state['item']) => $state['item'],
                        is_array($state['item']) && isset($state['item']['item']) => (string) $state['item']['item'],
                        is_array($state['item']) => json_encode($state['item']),
                        default => (string) $state['item'],
                    }),
                Forms\Components\Repeater::make("assessment_results.{$key}.recommendations")
                    ->label(__('Recommendations'))->schema([Forms\Components\TextInput::make('item')->required(),])
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state) => match (true) {
                        is_string($state['item']) => $state['item'],
                        is_array($state['item']) && isset($state['item']['item']) => (string) $state['item']['item'],
                        is_array($state['item']) => json_encode($state['item']),
                        default => (string) $state['item'],
                    }),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        // If the user is “premium”, only return what they created:
        if (auth()->user()?->hasRole('premium')) {
            return $query->where('id', auth()->id());
        }

        // Otherwise (super_admin, etc.), return all
        return $query;
    }
    public static function table(Table $table): Table
    {
        return $table->columns([

            Tables\Columns\TextColumn::make('total_fine')->money(__('sar'))->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('exportPdf')
                ->label(__('PDF'))->icon('heroicon-o-document-arrow-down')
                ->url(fn (AssessmentReport $record): string => route('assessment-reports.export-pdf', $record))
                ->openUrlInNewTab(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAssessmentReports::route('/'),
            'create' => Pages\CreateAssessmentReport::route('/create'),
            'view'   => Pages\ViewAssessmentReport::route('/{record}'),
            'edit'   => Pages\EditAssessmentReport::route('/{record}/edit'),
        ];
    }
}
