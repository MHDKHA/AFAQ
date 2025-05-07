<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('category.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('category.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('category.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('domain_id')
                    ->label(__('category.form.domain'))
                    ->options(Domain::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->label(__('category.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->label(__('category.form.order'))
                    ->integer()
                    ->default(fn () => \App\Models\Category::max('order') + 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain.name')
                    ->label(__('category.table.domain'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('category.table.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('category.table.order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('criteria_count')
                    ->label(__('category.table.criteria_count'))
                    ->counts('criteria'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('domain_id')
                    ->label(__('category.filters.domain'))
                    ->options(Domain::pluck('name', 'id')),
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
