<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriterionResource\Pages;
use App\Models\Category;
use App\Models\Criterion;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CriterionResource extends Resource
{
    protected static ?string $model = Criterion::class;
//    protected static ?string $recordTitleAttribute = 'question';
    public static function getNavigationGroup(): string
    {
        return __('tool.navigation_label');
    }
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('criterion.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('criterion.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('criterion.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('domain_id')
                    ->label(__('criterion.form.domain'))
                    ->relationship('domain', 'name')
                    ->required()
                    ->afterStateUpdated(fn (callable $set) => $set('category_id', null))
                    ->reactive()
                    ->live(),

                Forms\Components\Select::make('category_id')
                    ->label(__('criterion.form.category'))
                    ->options(function (Get $get, string $operation) {
                        $domainId = $get('domain_id');
                        if (! $domainId) {
                            return [];
                        }
                        return Category::where('domain_id', $domainId)
                            ->orderBy('order')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->live()
                    ->required()
                    ->searchable(),

                Forms\Components\Textarea::make('question')
                    ->label(__('criterion.form.question'))
                    ->required()
                    ->rows(3),

                Forms\Components\TextInput::make('order')
                    ->label(__('criterion.form.order'))
                    ->integer()
                    ->default(fn () => \App\Models\Criterion::max('order') + 1),

                Forms\Components\Textarea::make('true_case')
                    ->label(__('criterion.form.true_case'))
                    ->required()
                    ->rows(3),

                Forms\Components\Textarea::make('false_case')
                    ->label(__('criterion.form.false_case'))
                    ->required()
                    ->rows(3),
            ])
            ->columns(2);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label(__('criterion.table.order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.domain.name')
                    ->label(__('criterion.table.domain'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('criterion.table.category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('question')
                    ->label(__('criterion.table.question'))
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('domain')
                    ->label(__('criterion.filters.domain'))
                    ->options(Domain::pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn (Builder $query, $value): Builder => $query->whereHas(
                                    'category',
                                    fn (Builder $query): Builder => $query->where('domain_id', $value)
                                )
                            );
                    }),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('criterion.filters.category'))
                    ->options(Category::pluck('name', 'id')),
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
            'index' => Pages\ListCriteria::route('/'),
            'create' => Pages\CreateCriterion::route('/create'),
            'edit' => Pages\EditCriterion::route('/{record}/edit'),
        ];
    }
}
