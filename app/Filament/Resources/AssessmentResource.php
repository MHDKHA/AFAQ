<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use App\Models\Company;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    public static function getNavigationGroup(): string
    {
        return __('assessment.navigation_label');
    }
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('assessment.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('assessment.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('assessment.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('tool_id')
                    ->label(__('assessment.form.tool'))
                    ->relationship('tool', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label(__('assessment.form.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('name_ar')  // Updated from en_name to name_ar
                ->label(__('assessment.form.name_ar'))  // Updated label
                ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('user_id')  // Added user relationship
                ->relationship('user', 'name')
                    ->required()
                    ->label(__('assessment.form.user')),

                Forms\Components\Select::make('company_id')  // Added company relationship
                ->relationship('company', 'name')
                    ->required()
                    ->label(__('assessment.form.company')),

                Forms\Components\DatePicker::make('date')
                    ->label(__('assessment.form.date'))
                    ->required()
                    ->default(now()),

                Forms\Components\Textarea::make('description')
                    ->label(__('assessment.form.description'))
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->contentGrid(['2xl' => 3])
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('assessment.table.name'))
                        ->getStateUsing(fn ($record) => App::getLocale() === 'en'
                            ? $record->name
                            : $record->name_ar  // Updated from en_name to name_ar
                        ),

                    Tables\Columns\TextColumn::make('company.name')  // Added company name
                    ->label(__('assessment.table.company')),

                    Tables\Columns\TextColumn::make('user.name')  // Added user name
                    ->label(__('assessment.table.user')),

                    Tables\Columns\TextColumn::make('completion_percentage')
                        ->label(__('assessment.table.completion'))
                        ->formatStateUsing(fn ($state) => "{$state}%"),

                    Tables\Columns\TextColumn::make('available_count')
                        ->label(__('assessment.table.available'))
                        ->formatStateUsing(fn ($state, $record) => "{$state}/{$record->items->count()}"),

                    Tables\Columns\TextColumn::make('unavailable_count')
                        ->label(__('assessment.table.unavailable'))
                        ->formatStateUsing(fn ($state, $record) => "{$state}/{$record->items->count()}"),

                    Tables\Columns\IconColumn::make('report')  // Added indicator for report existence
                    ->label(__('assessment.table.has_report'))
                        ->boolean()
                        ->getStateUsing(fn ($record) => $record->report()->exists()),
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')  // Added company filter
                ->relationship('company', 'name')
                    ->label(__('assessment.filters.company')),

                Tables\Filters\SelectFilter::make('user_id')  // Added user filter
                ->relationship('user', 'name')
                    ->label(__('assessment.filters.user')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('fill')
                    ->label(__('assessment.actions.fill'))
                    ->url(fn (Assessment $record) => route('filament.afaq.resources.assessments.fill', $record))
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\Action::make('view')
                    ->label(__('assessment.actions.view'))
                    ->url(fn (Assessment $record) => route('filament.afaq.resources.assessments.view', $record))
                    ->icon('heroicon-o-eye')
                    ->color('success'),

                Tables\Actions\Action::make('create_report')  // Added action to create report
                ->label(__('assessment.actions.create_report'))
                    ->url(fn (Assessment $record) => route('filament.afaq.resources.assessment-reports.create', ['assessment_id' => $record->id]))
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('warning')
                    ->hidden(fn (Assessment $record) => $record->report()->exists()),

                Tables\Actions\Action::make('view_report')  // Added action to view report
                ->label(__('assessment.actions.view_report'))
                    ->url(fn (Assessment $record) => route('filament.afaq.resources.assessment-reports.edit', ['record' => $record->report->id]))
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('primary')
                    ->hidden(fn (Assessment $record) => !$record->report()->exists()),

                Tables\Actions\Action::make('view_dashboard')
                    ->label('عرض لوحة المعلومات')
                    ->url(route('filament.afaq.pages.assessment-dashboard'))
                    ->icon('heroicon-o-chart-bar')
                    ->color('success'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Can add relations to assessment items here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
            'fill' => Pages\FillAssesment::route('/{record}/fill'),
            'view' => Pages\ViewAssesment::route('/{record}/view'),
        ];
    }
}
