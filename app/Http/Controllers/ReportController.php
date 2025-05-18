<?php

namespace App\Http\Controllers;

use App\Models\AssessmentReport;
use App\Services\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use QuickChart;

class ReportController extends Controller
{
    protected Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function exportPdf(AssessmentReport $record, Request $request)
    {
        // 1. Determine target locale
        $locale = $request->get('locale')
            ?? session('locale', 'ar');
        App::setLocale($locale);
        session(['locale' => $locale]);

        // 2. Normalize your record data
        $data = $this->normalizeRecordData($record);


        $overallChartUrl  = $this->buildOverallChartUrl();
        $managmentUrl     = $this->buildViolationsChartUrl(1, 2.89);
        $leadersUrl       = $this->buildViolationsChartUrl(8, 32.08);
        $planningUrl      = $this->buildViolationsChartUrl(1, 1.45);
        $operationsUrl    = $this->buildViolationsChartUrl(8, 63.58);

        // 5. Render the appropriate Blade
        $view = $locale === 'en'
            ? 'reports.assessment-report-en'
            : 'reports.assessment-report';  // your Arabic view
        $html = view($view, compact(
            'data',
            'overallChartUrl',
            'managmentUrl',
            'leadersUrl',
            'planningUrl',
            'operationsUrl'
        ))->render();

        // 6. Send to mPDF
        $mpdf = $this->initMpdf();
        $mpdf->SetDirectionality($locale === 'ar' ? 'rtl' : 'ltr');
        $mpdf->WriteHTML($html);

        $filename = "assessment-report-{$record->company_name}.pdf";
        $output   = $mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN);

        return response($output, 200, ['Content-Type' => 'application/pdf']);
    }

    private function buildOverallChartUrl(): string
    {


        $labels = ['التحسين', 'تقييم الأداء', 'التشغيل', 'الدعم', 'التخطيط', 'القيادة', 'التأسيس الإداري'];
        $datasets = [
            ['label' => 'متوفر', 'data' => [1, 0, 3, 0, 0, 3, 1], 'backgroundColor' => 'rgba(54, 162, 235, 0.8)'],
            ['label' => 'غير متوفر', 'data' => [6, 6, 10, 5, 3, 4, 7], 'backgroundColor' => 'rgba(255, 99, 132, 0.8)'],
            ['label' => 'لا ينطبق', 'data' => [0, 0, 0, 0, 0, 0, 0], 'backgroundColor' => 'rgba(107, 114, 128, 0.8)'],
        ];

        $config = [
            'type' => 'bar',
            'data' => compact('labels', 'datasets'),
            'options' => [
                'color' => 'black',
                'plugins' => [
                    'legend' => ['position' => 'bottom', 'labels' => ['color' => 'black'], 'rtl' => true],
                    'tooltip' => ['rtl' => true],
                ],
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'position' => 'left',
                        'beginAtZero' => true,
                        'ticks' => ['stepSize' => 1, 'color' => 'black'],
                        'title' => ['display' => false]
                    ],
                    'percentageAxis' => [
                        'type' => 'linear',
                        'position' => 'right',
                        'beginAtZero' => true,
                        'ticks' => ['stepSize' => 1, 'color' => 'black'],
                        'title' => ['display' => true, 'text' => 'النسبة (%)', 'color' => 'black']
                    ],
                ],
            ],
        ];
        $chart = new QuickChart(array(
            'width' => 800,
            'height' => 400
        ));

        $chart->setConfig($config);

        return $chart->getUrl();
    }

    private function buildViolationsChartUrl(int $violationCount = 8, float $violationPercent = 32.08): string
    {
        $maxYValue = ceil(max($violationCount, $violationPercent, 10) * 1.09);

        $config = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Violation'],
                'datasets' => [
                    [
                        'label' => 'Violation',
                        'data' => [$violationCount],
                        'backgroundColor' => 'rgba(46, 17, 156, 0.6)',
                        'borderColor' => 'rgba(46, 17, 156, 0.6)',
                        'borderWidth' => 3,
                        'yAxisID' => 'Y1',
                    ],
                    [
                        'label' => 'Violation Percent',
                        'data' => [$violationPercent],
                        'backgroundColor' => 'rgba(196, 28, 28, 0.6)',
                        'borderColor' => 'rgba(196, 28, 28, 0.6)',
                        'borderWidth' => 3,
                        'yAxisID' => 'Y2',
                    ],
                ],
            ],
            'options' => [
                'title' => ['display' => false],
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'fontSize' => 12,
                        'fontColor' => '#666666',
                        'fontStyle' => 'normal',
                    ],
                ],
                'scales' => [
                    'xAxes' => [[
                        'type' => 'category',
                        'gridLines' => ['color' => 'rgba(0, 0, 0, 0.1)'],
                        'ticks' => ['fontSize' => 12, 'fontColor' => '#666666'],
                    ]],
                    'yAxes' => [
                        [
                            'id' => 'Y1',
                            'type' => 'linear',
                            'position' => 'left',
                            'ticks' => [
                                'beginAtZero' => true,
                                'max' => $maxYValue,
                                'fontSize' => 12,
                                'fontColor' => '#666666',
                            ],
                            'gridLines' => ['color' => 'rgba(0, 0, 0, 0.1)'],
                        ],
                        [
                            'id' => 'Y2',
                            'type' => 'linear',
                            'position' => 'right',
                            'ticks' => [
                                'beginAtZero' => true,
                                'max' => $maxYValue,
                                'fontSize' => 12,
                                'fontColor' => '#666666',
                            ],
                            'gridLines' => ['drawOnChartArea' => false],
                        ],
                    ],
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'align' => 'top',
                        'anchor' => 'end',
                        'color' => '#000000',
                        'font' => [
                            'family' => 'sans-serif',
                            'size' => 14,
                            'style' => 'bold',
                        ],
                    ],
                ],
            ],
            'plugins' => ['datalabels'], // make sure datalabels plugin is loaded
        ];

        $chart = new QuickChart([
            'width' => 600,
            'height' => 400,
            'devicePixelRatio' => 1.0,
        ]);

        $chart->setConfig(json_encode($config, JSON_UNESCAPED_UNICODE));
        $chart->setBackgroundColor('transparent');

        return $chart->getUrl();
    }




//    private function buildQuickChartUrl(array $config, int $width, int $height): string
//    {
//        // Use base64 encoding for the config to avoid URL length limitations
//        $c = base64_encode(json_encode($config));
//        return "https://quickchart.io/chart?width={$width}&height={$height}&encoding=base64&c={$c}&v=2";
//    }

    private function initMpdf(): Mpdf
    {
        $configVars = (new ConfigVariables())->getDefaults();
        $fontVars = (new FontVariables())->getDefaults();

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'calibri',
        ]);
    }

    private function normalizeRecordData(AssessmentReport $record)
    {
        $data = clone $record;

        $data->work_environment = $this->normalizeArrayItems($record->work_environment ?? []);
        $data->assessment_results = $this->normalizeNestedItems($record->assessment_results ?? []);
        $data->follow_up_services = $this->normalizeArrayItems($record->follow_up_services ?? []);
        $data->panel_notes = $this->normalizeArrayItems($record->panel_notes ?? []);

        return $data;
    }

    private function normalizeNestedItems(array $sections): array
    {
        foreach ($sections as $key => $section) {
            $sections[$key]['notes'] = $this->normalizeArrayItems($section['notes'] ?? []);
            $sections[$key]['recommendations'] = $this->normalizeArrayItems($section['recommendations'] ?? []);
        }

        return $sections;
    }

    private function normalizeArrayItems(array $items): array
    {
        return array_map(fn($item) => is_array($item) ? ($item['item'] ?? $item) : $item, $items);
    }




}
