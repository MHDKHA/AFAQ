<!-- resources/views/pdfs/assessment-report.blade.php -->

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data->company_name }} - تقرير التقييم</title>
    <style>
        body { font-family: calibri, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; direction: rtl; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { color: #003366; margin-bottom: 10px; }
        .logo { position: absolute; top: 0; left: 0; width: 80px; }
        .company-info p { margin: 5px 0; }
        .section { margin-bottom: 30px; }
        .section h3 { background-color: #003366; color: #fff; padding: 10px; border-radius: 5px; }
        .section h4 { color: #003366; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; }
        ul { padding-right: 20px; }
        li { margin-bottom: 5px; }
        .page-break { page-break-after: always; }
        .footer { text-align: center; font-size: 12px; margin-top: 30px; padding-top: 10px; border-top: 1px solid #ccc; }
        .page-header img { height: 40px; width: 40px; position: fixed; left: 0; }
    </style>
</head>
<body>
@include('pdfs.partials.header')

<div class="header">
    <h1>تقرير التقييم</h1>
    <h2>{{ $data->company_name }}</h2>
    <h3>{{ $data->company_name_ar }}</h3>
</div>

<div class="company-info">
    <p><strong>المسؤول:</strong> {{ $data->stakeholder_name }}</p>
    <p><strong>المسؤول (عربي):</strong> {{ $data->stakeholder_name_ar }}</p>
    <p>{{ $data->acknowledgment }}</p>
    <p>{{ $data->getTranslation('acknowledgment', 'ar') }}</p>
</div>

<div class="section">
    <h3>معلومات العميل</h3>
    <table>
        @foreach($data->client_info as $key => $value)
            <tr>
                <th>{{ __('reports.client_info.'.$key) }}</th>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table>
</div>

<div class="page-break"></div>

<div class="section">
    <h3>المرفقات</h3>
    @if(!empty($data->attachments))
        <ul>
            @foreach($data->attachments as $file)
                <li><a href="{{ storage_path('app/'.$file) }}">{{ basename($file) }}</a></li>
            @endforeach
        </ul>
    @else
        <p>لا يوجد مرفقات</p>
    @endif
</div>

<div class="page-break"></div>

<div class="section">
    <h3>تحليل بيئة العمل</h3>
    @foreach(['pros'=>'نقاط القوة','cons'=>'نقاط الضعف','improvement_areas'=>'مجالات التحسين','risk_areas'=>'مناطق الخطر'] as $field => $title)
        <h4>{{ $title }}</h4>
        <ul>
            @foreach($data->work_environment[$field] as $item)
                <li>{{ is_array($item) ? ($item['item'] ?? '') : $item }}</li>
            @endforeach
        </ul>
    @endforeach
</div>

<div class="page-break"></div>

<div class="section">
    <h3>لوحة النتائج العامة</h3>
    <h4>ملاحظات:</h4>
    <ul>
        @foreach($data->panel_notes as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>
    <div class="chart-container">
        <img src="{{ $overallChartUrl }}" alt="Overall Chart" style="width:100%; max-width:800px;">
    </div>
</div>

{{-- Repeat each criterion block similarly, using loops and include partials for charts --}}

<div class="page-break"></div>

<div class="section">
    <h3>المخالفات المتوقعة</h3>
    <table>
        <thead>
        <tr>
            <th>المخالفة</th>
            <th>الغرامة (ريال)</th>
            <th>الحل</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data->expected_violations as $violation)
            <tr>
                <td>{{ $violation['violation'] }}</td>
                <td>{{ number_format($violation['fine'], 2) }}</td>
                <td>{{ $violation['solution'] }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>إجمالي الغرامات</td>
            <td colspan="2">{{ number_format($data->total_fine,2) }} ريال</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="page-break"></div>

<div class="section">
    <h3>الخدمات اللاحقة للتقرير</h3>
    <ul>
        @foreach($data->follow_up_services as $service)
            <li>{{ is_array($service) ? ($service['item'] ?? '') : $service }}</li>
        @endforeach
    </ul>
</div>

<div class="page-break"></div>

<div class="section">
    <h3>معلومات المقيّم</h3>
    <p>التاريخ: {{ now()->toDateString() }}</p>
</div>

<footer class="footer">
    <p>© {{ date('Y') }} - جميع الحقوق محفوظة</p>
</footer>
</body>
</html>
