<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assesment;
use App\Models\Criterion;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assesment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

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
                Forms\Components\TextInput::make('name')
                    ->label(__('assessment.form.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('en_name')
                    ->label(__('assessment.form.en_name'))
                    ->required()
                    ->maxLength(255),

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
            ->contentGrid(['xl' => 3])
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('assessment.table.name'))
                        ->getStateUsing(fn ($record) => App::getLocale() === 'en'
                            ? $record->en_name
                            : $record->name
                        )
                       ,
                    Tables\Columns\TextColumn::make('completion_percentage')
                        ->label(__('assessment.table.completion'))
                        ->formatStateUsing(fn ($state) => "{$state}%"),

                    Tables\Columns\TextColumn::make('available_count')
                        ->label(__('assessment.table.available'))
                        ->formatStateUsing(fn ($state, $record) => "{$state}/{$record->items->count()}"),

                    Tables\Columns\TextColumn::make('unavailable_count')
                        ->label(__('assessment.table.unavailable'))
                        ->formatStateUsing(fn ($state, $record) => "{$state}/{$record->items->count()}"),

                ]),
            ])
            ->filters([])

            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('fill')
                    ->label(__('assessment.actions.fill'))
                    ->url(fn (Assesment $record) => route('filament.afaq.resources.assessments.fill', $record))
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\Action::make('view')
                    ->label(__('assessment.actions.view'))
                    ->url(fn (Assesment $record) => route('filament.afaq.resources.assessments.view', $record))
                    ->icon('heroicon-o-eye')
                    ->color('success'),

                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([

            ])

            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
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
