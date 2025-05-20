<?php



namespace App\Filament\Pages;

use App\Filament\Widgets\AssessmentAvailabilityWidget;
use App\Filament\Widgets\AssessmentDomainDistributionWidget;
use App\Filament\Widgets\AssessmentRadarWidget;
use App\Models\Assessment;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\View\View;

class AssessmentDashboard extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.assessment-dashboard';
    protected static ?string $title = 'لوحة التحكم في التقييم';

    public static function getNavigationGroup(): string
    {
        return __('assessment.navigation_label');
    }
    protected static ?string $navigationLabel = 'لوحة التقييم';
    protected static ?int $navigationSort = 0;

    public ?Assessment $assessment = null;

    public function mount(): void
    {
        // Get the latest assessment or a specific one
        $this->assessment = Assessment::latest()->first();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AssessmentAvailabilityWidget::class,
            AssessmentDomainDistributionWidget::class,
            AssessmentRadarWidget::class,
        ];
    }

}
