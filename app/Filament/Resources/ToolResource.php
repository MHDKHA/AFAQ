<?php
// app/Filament/Resources/ToolResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ToolResource\Pages;
use App\Models\Tool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';


    public static function getNavigationGroup(): string
    {
        return __('tool.navigation_label');
    }
    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('tool.navigation_label');
    }



    public static function getModelLabel(): string
    {
        return __('tool.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('tool.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('tool.form.name'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('slug', Str::slug($state))
                    )
                    ->maxLength(255),

                Forms\Components\TextInput::make('name_ar')
                    ->label(__('tool.form.name_ar'))
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label(__('tool.form.slug'))
                    ->required()
                    ->unique(Tool::class, 'slug', fn ($record) => $record)
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label(__('tool.form.description'))
                    ->rows(3),

                Forms\Components\Textarea::make('description_ar')
                    ->label(__('tool.form.description_ar'))
                    ->rows(3),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('tool.form.is_active'))
                    ->default(true),

                Forms\Components\TextInput::make('role_name')
                    ->label(__('tool.form.role_name'))
                    ->helperText(__('tool.form.role_name_help'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tool.table.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('tool.table.name_ar'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('role_name')
                    ->label(__('tool.table.role_name')),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('tool.table.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tool.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filters as needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relations as needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTools::route('/'),
            'create' => Pages\CreateTool::route('/create'),
            'edit' => Pages\EditTool::route('/{record}/edit'),
        ];
    }
}
