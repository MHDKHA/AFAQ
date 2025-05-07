<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentReportResource\Pages;
use App\Models\AssessmentReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AssessmentReportResource extends Resource
{
    protected static ?string $model = AssessmentReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Assessment';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stakeholder_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('acknowledgment')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Client Information')
                    ->schema([
                        Forms\Components\TextInput::make('client_info.work_type')
                            ->label('Work Type')
                            ->required(),
                        Forms\Components\TextInput::make('client_info.work_schedule')
                            ->label('Work Schedule')
                            ->required(),
                        Forms\Components\TextInput::make('client_info.break_schedule')
                            ->label('Break Schedule'),
                        Forms\Components\TextInput::make('client_info.branches')
                            ->label('Number of Branches')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('client_info.work_days')
                            ->label('Work Days')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('client_info.day_off')
                            ->label('Days Off'),
                        Forms\Components\TextInput::make('client_info.male_emp')
                            ->label('Male Employees')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('client_info.female_emp')
                            ->label('Female Employees')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('client_info.collaborators')
                            ->label('Collaborators')
                            ->numeric(),
                    ])->columns(2),

                Forms\Components\Section::make('Attachments')
                    ->schema([
                        Forms\Components\Repeater::make('attachments')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->defaultItems(0)
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),
                    ]),

                Forms\Components\Section::make('Work Environment Analysis')
                    ->schema([
                        Forms\Components\Repeater::make('work_environment.pros')
                            ->label('Strengths')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),

                        Forms\Components\Repeater::make('work_environment.cons')
                            ->label('Weaknesses')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),

                        Forms\Components\Repeater::make('work_environment.improvement_areas')
                            ->label('Improvement Areas')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),

                        Forms\Components\Repeater::make('work_environment.risk_areas')
                            ->label('Risk Areas')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),
                    ]),

                Forms\Components\Section::make('Panel Notes')
                    ->schema([
                        Forms\Components\Repeater::make('panel_notes')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),
                    ]),

                Forms\Components\Section::make('Assessment Results')
                    ->schema([
                        self::createAssessmentSection('administrative_foundation', 'Administrative Foundation'),
                        self::createAssessmentSection('leadership', 'Leadership'),
                        self::createAssessmentSection('planning', 'Planning'),
                        self::createAssessmentSection('human_resources', 'Human Resources'),
                        self::createAssessmentSection('operations', 'Operations'),
                        self::createAssessmentSection('performance_evaluation', 'Performance Evaluation'),
                        self::createAssessmentSection('improvement', 'Improvement'),
                    ]),

                Forms\Components\Section::make('Expected Violations')
                    ->schema([
                        Forms\Components\Repeater::make('expected_violations')
                            ->schema([
                                Forms\Components\TextInput::make('violation')
                                    ->required(),
                                Forms\Components\TextInput::make('fine')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('solution')
                                    ->required(),
                            ])
                            ->columns(3),
                        Forms\Components\TextInput::make('total_fine')
                            ->numeric()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Follow-up Services')
                    ->schema([
                        Forms\Components\Repeater::make('follow_up_services')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),
                    ]),
            ]);
    }

    private static function createAssessmentSection(string $key, string $label): Forms\Components\Fieldset
    {
        return Forms\Components\Fieldset::make($label)
            ->schema([
                Forms\Components\TextInput::make("assessment_results.{$key}.achievement_percentage")
                    ->label('Achievement Percentage')
                    ->numeric()
                    ->required(),
                Forms\Components\Repeater::make("assessment_results.{$key}.notes")
                    ->label('Notes')
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),
                Forms\Components\Repeater::make("assessment_results.{$key}.recommendations")
                    ->label('Recommendations')
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state): ?string => $state['item'] ?? null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stakeholder_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_fine')
                    ->money('sar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessmentReports::route('/'),
            'create' => Pages\CreateAssessmentReport::route('/create'),
            'view' => Pages\ViewAssessmentReport::route('/{record}'),
            'edit' => Pages\EditAssessmentReport::route('/{record}/edit'),
        ];
    }
}
