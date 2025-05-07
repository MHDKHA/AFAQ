<?php

namespace App\Filament\Resources\AssessmentReportResource\Pages;

use App\Filament\Resources\AssessmentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssessmentReport extends EditRecord
{
    protected static string $resource = AssessmentReportResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Transform attachments for the repeater
        if (isset($data['attachments'])) {
            $data['attachments'] = array_map(fn ($item) => ['item' => $item], $data['attachments']);
        }

        // Transform work environment data for repeaters
        foreach (['pros', 'cons', 'improvement_areas', 'risk_areas'] as $key) {
            if (isset($data['work_environment'][$key])) {
                $data['work_environment'][$key] = array_map(fn ($item) => ['item' => $item], $data['work_environment'][$key]);
            }
        }

        // Transform panel notes for repeater
        if (isset($data['panel_notes'])) {
            $data['panel_notes'] = array_map(fn ($note) => ['item' => $note], $data['panel_notes']);
        }

        // Transform assessment results for repeaters
        foreach ($data['assessment_results'] as $key => $section) {
            if (isset($section['notes'])) {
                $data['assessment_results'][$key]['notes'] = array_map(fn ($note) => ['item' => $note], $section['notes']);
            }
            if (isset($section['recommendations'])) {
                $data['assessment_results'][$key]['recommendations'] = array_map(fn ($rec) => ['item' => $rec], $section['recommendations']);
            }
        }

        // Transform follow-up services for repeater
        if (isset($data['follow_up_services'])) {
            $data['follow_up_services'] = array_map(fn ($service) => ['item' => $service], $data['follow_up_services']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Process attachments to flatten from array of objects to array of strings
        if (isset($data['attachments'])) {
            $data['attachments'] = array_map(fn ($attachment) => $attachment['item'], $data['attachments']);
        }

        // Process work environment arrays
        foreach (['pros', 'cons', 'improvement_areas', 'risk_areas'] as $key) {
            if (isset($data['work_environment'][$key])) {
                $data['work_environment'][$key] = array_map(fn ($item) => $item['item'], $data['work_environment'][$key]);
            }
        }

        // Process panel notes
        if (isset($data['panel_notes'])) {
            $data['panel_notes'] = array_map(fn ($note) => $note['item'], $data['panel_notes']);
        }

        // Process assessment results
        foreach ($data['assessment_results'] as $key => $section) {
            if (isset($section['notes'])) {
                $data['assessment_results'][$key]['notes'] = array_map(fn ($note) => $note['item'], $section['notes']);
            }
            if (isset($section['recommendations'])) {
                $data['assessment_results'][$key]['recommendations'] = array_map(fn ($rec) => $rec['item'], $section['recommendations']);
            }
        }

        // Process follow-up services
        if (isset($data['follow_up_services'])) {
            $data['follow_up_services'] = array_map(fn ($service) => $service['item'], $data['follow_up_services']);
        }

        return $data;
    }
}
