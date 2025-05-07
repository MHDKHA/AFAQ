<?php

namespace App\Filament\Resources\AssessmentReportResource\Pages;

use App\Filament\Resources\AssessmentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use App\Models\AssessmentReport;

class ListAssessmentReports extends ListRecords
{
    protected static string $resource = AssessmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}



class ViewAssessmentReport extends ViewRecord
{
    protected static string $resource = AssessmentReportResource::class;
2313121
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}



