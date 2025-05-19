{{-- resources/views/emails/assessment-completed.blade.php --}}
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #777;
            font-size: 14px;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
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
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>تقرير التقييم</h1>
        <h2>{{ $assessment->name }}</h2>
    </div>

    <div class="content">
        <p>مرحباً،</p>
        <p>تم إكمال تقييم "{{ $assessment->name }}" بنجاح بتاريخ {{ $assessment->date->format('Y-m-d') }}.</p>
        <p>يحتوي هذا البريد الإلكتروني على مرفق يضم تقرير التقييم كامل بصيغة PDF.</p>

        <p>ملخص النتائج:</p>
        <ul>
            <li>إجمالي المعايير: {{ $assessment->items()->count() }}</li>
            <li>المعايير المتوفرة: {{ $assessment->getAvailableCountAttribute() }}</li>
            <li>المعايير غير المتوفرة: {{ $assessment->getUnavailableCountAttribute() }}</li>
        </ul>

        <p>يمكنك الاطلاع على التفاصيل الكاملة في التقرير المرفق.</p>
    </div>

    <div class="footer">
        <p>هذا بريد إلكتروني تلقائي، يرجى عدم الرد عليه.</p>
        <p>&copy; {{ date('Y') }} - جميع الحقوق محفوظة</p>
    </div>
</div>
</body>
</html>
