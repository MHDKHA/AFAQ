{{-- resources/views/assessments/print.blade.php --}}
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير التقييم: {{ $assessment->name }}</title>
    <style>
        body {
            font-family: 'Cairo', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f9f9f9;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: 60px;
        }

        .assessment-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .assessment-info div {
            flex: 1;
            padding: 10px;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            text-align: center;
        }

        .stat-box {
            flex: 1;
            padding: 15px;
            border-radius: 5px;
            margin: 0 5px;
        }

        .success { background-color: #d1e7dd; }
        .danger { background-color: #f8d7da; }
        .info { background-color: #cff4fc; }
        .warning { background-color: #fff3cd; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
        }

        .domain-header {
            background-color: #e9ecef;
            padding: 10px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }

        .category-header {
            background-color: #f8f9fa;
            padding: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-right: 4px solid #6c757d;
            font-weight: bold;
        }

        .available {
            color: green;
            font-weight: bold;
        }

        .unavailable {
            color: red;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .chart {
            width: 100%;
            margin: 20px 0;
            text-align: center;
        }

        .chart img {
            max-width: 100%;
            height: auto;
        }

        .toc {
            margin: 20px 0;
        }

        .toc-item {
            margin-bottom: 5px;
        }

        .toc-page {
            float: left;
        }

        .highlights {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }

        .highlight-item {
            margin-bottom: 15px;
        }

        .highlight-number {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            display: block;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .page-break {
                page-break-after: always;
            }
        }

    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>تقرير التقييم</h1>
        <h2>{{ $assessment->name }}</h2>
        <p>تاريخ التقييم: {{ $assessment->date->format('Y-m-d') }}</p>
    </div>

    <div class="assessment-info">
        <div>
            <strong>اسم التقييم:</strong> {{ $assessment->name }}
        </div>
        <div>
            <strong>تاريخ التقييم:</strong> {{ $assessment->date->format('Y-m-d') }}
        </div>
        <div>
            <strong>تاريخ إنشاء التقييم:</strong> {{ $assessment->created_at->format('Y-m-d') }}
        </div>
    </div>

    <div class="stats">
        <div class="stat-box success">
            <h3>العناصر المتوفرة</h3>
            <p>{{ $availableItems }} من {{ $totalItems }}</p>
            <p>{{ $availableRate }}%</p>
        </div>
        <div class="stat-box danger">
            <h3>العناصر غير المتوفرة</h3>
            <p>{{ $unavailableItems }} من {{ $totalItems }}</p>
            <p>{{ $unavailableRate }}%</p>
        </div>
        <div class="stat-box info">
            <h3>اكتمال التقييم</h3>
            <p>{{ $totalItems }} من 39</p>
            <p>{{ $completionRate }}%</p>
        </div>
    </div>

    @foreach($domains as $domain)
{{--        <div class="domain-header">{{ $domain->name }}</div>--}}

        @foreach($domain->categories as $category)
            <div class="category-header">{{ $category->name }}</div>

            <table>
                <thead>
                <tr>
                    <th width="5%">م</th>
                    <th width="60%">السؤال التدقيقي</th>
                    <th width="15%">حالة التوفر</th>
                    <th width="20%">ملاحظات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($category->criteria as $criterion)
                    @php
                        $assessmentItem = $assessmentItems->where('criteria_id', $criterion->id)->first();
                    @endphp
                    <tr>
                        <td>{{ $criterion->order }}</td>
                        <td>{{ $criterion->question }}</td>
                        <td class="{{ $assessmentItem && $assessmentItem->is_available ? 'available' : 'unavailable' }}">
                            {{ $assessmentItem && $assessmentItem->is_available ? 'نعم' : 'لا' }}
                        </td>
                        <td>{{ $assessmentItem ? $assessmentItem->notes : '' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()">طباعة التقرير</button>
    </div>
</div>
</body>
</html>
