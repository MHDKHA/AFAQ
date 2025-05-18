<?php

namespace App\Filament\Resources\AssessmentReportResource\Pages;

use App\Filament\Resources\AssessmentReportResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class ListAssessmentReports extends ListRecords
{
    protected static string $resource = AssessmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
            Actions\Action::make('exportPdf')
                ->label(__('PDF'))
                ->icon('heroicon-o-document-arrow-down')
                ->action(function ($record) {
                    return $this->exportPdf($record);
                }),
        ];
    }

    protected function exportPdf($report)
    {
        // Use the route to ensure consistent handling
        return redirect()->route('assessment-reports.export-pdf', $report);
    }
}
