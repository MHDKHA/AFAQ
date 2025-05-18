<?php

namespace App\Filament\Resources\AssessmentReportResource\Pages;

use App\Filament\Resources\AssessmentReportResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class ViewAssessmentReport extends ViewRecord
{
    protected static string $resource = AssessmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('exportPdf')
                ->label(__('Export PDF'))
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    return $this->exportPdf();
                }),
        ];
    }

    protected function exportPdf()
    {
        $report = $this->record;

        // Use the route to ensure consistent handling
        return redirect()->route('assessment-reports.export-pdf', $report);
    }
}
