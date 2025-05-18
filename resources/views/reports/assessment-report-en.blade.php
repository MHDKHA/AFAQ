<!-- resources/views/pdfs/assessment-report-en.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data->company_name }} - Assessment Report</title>
    <style>
        body { font-family: calibri, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .section h3 { background-color: #003366; color: #fff; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .page-break { page-break-after: always; }
        .footer { text-align: center; font-size: 12px; margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; }
        .page-header img { position: fixed; right: 0; width: 80px; }
    </style>
</head>
<body>
@include('pdfs.partials.header-ltr')

<div class="header">
    <h1>Assessment Report</h1>
    <h2>{{ $data->company_name }}</h2>
</div>

<div class="section">
    <h3>Client Information</h3>
    @include('pdfs.partials.client-info', ['info' => $data->client_info])
</div>

<div class="page-break"></div>

<div class="section">
    <h3>Attachments</h3>
    @if($data->attachments)
        <ul>
            @foreach($data->attachments as $file)
                <li><a href="{{ storage_path('app/'.$file) }}">{{ basename($file) }}</a></li>
            @endforeach
        </ul>
    @else
        <p>No attachments</p>
    @endif
</div>

<div class="page-break"></div>

<div class="section">
    <h3>Work Environment Analysis</h3>
    @foreach(['pros'=>'Strengths','cons'=>'Weaknesses','improvement_areas'=>'Improvement Areas','risk_areas'=>'Risk Areas'] as $field => $title)
        <h4>{{ $title }}</h4>
        <ul>
            @foreach($data->work_environment[$field] as $item)
                <li>{{ is_array($item) ? ($item['item'] : $item) }}</li>
            @endforeach
        </ul>
    @endforeach
</div>

<div class="page-break"></div>

<div class="section">
    <h3>General Results Panel</h3>
    <ul>
        @foreach($data->panel_notes as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>
    <img src="{{ $overallChartUrl }}" alt="Overall Chart" style="width:100%; max-width:800px;">
</div>

{{-- Include criterion sections and other components similarly --}}

<div class="page-break"></div>

<div class="section">
    <h3>Expected Violations</h3>
    @include('pdfs.partials.violations-ltr', ['violations' => $data->expected_violations, 'total' => $data->total_fine])
</div>

<div class="page-break"></div>

<div class="section">
    <h3>Post-Report Services</h3>
    <ul>
        @foreach($data->follow_up_services as $service)
            <li>{{ is_array($service) ? ($service['item'] : $service) }}</li>
        @endforeach
    </ul>
</div>

<div class="page-break"></div>

<div class="section">
    <h3>Assessor Information</h3>
    <p>Prepared on: {{ now()->toDateString() }}</p>
</div>

<footer class="footer">
    <p>© {{ date('Y') }} All Rights Reserved</p>
</footer>
</body>
</html>
