<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Table;
use TomatoPHP\FilamentTranslationComponent\Components\Translation;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;


    public static function getNavigationLabel(): string
    {
        return __('domain.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('domain.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('domain.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('domain.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_ar')
                    ->label(__('domain.name_ar'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->label(__('domain.order'))
                    ->integer()
                    ->default(0),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColumnGroup::make(__('domain.table.name'), [
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('domain.name'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('name_ar')
                        ->label(__('domain.name_ar'))
                        ->searchable(),
                ]),

                Tables\Columns\TextColumn::make('order')
                    ->label(__('domain.order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories_count')
                    ->label(__('domain.categories_count')) // Changed from 'items_count'
                    ->counts('categories'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
