<ul>
    @foreach($report->assessment_results['leadership']['recommendations'] as $rec)
        <li>{{ $rec }}</li>
    @endforeach
</ul>
</div>

<div class="section">
    <h3>نتائج معيار الموارد البشرية</h3>
    <p>نسبة تحقق المعيار: {{ $report->assessment_results['human_resources']['achievement_percentage'] }}%</p>

    <h4>أبرز النتائج:</h4>
    <ul>
        @foreach($report->assessment_results['human_resources']['notes'] as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>

    <h4>أبرز التوصيات:</h4>
    <ul>
        @foreach($report->assessment_results['human_resources']['recommendations'] as $rec)
            <li>{{ $rec }}</li>
        @endforeach
    </ul>
</div>

<div class="section">
    <h3>نتائج معيار الخدمات</h3>
    <p>نسبة تحقق المعيار: {{ $report->assessment_results['services']['achievement_percentage'] }}%</p>

    <h4>أبرز النتائج:</h4>
    <ul>
        @foreach($report->assessment_results['services']['notes'] as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>

    <h4>أبرز التوصيات:</h4>
    <ul>
        @foreach($report->assessment_results['services']['recommendations'] as $rec)
            <li>{{ $rec }}</li>
        @endforeach
    </ul>
</div>

<div class="page-break"></div>

<div class="section">
    <h3>نتائج معيار التقنية والرقمنة</h3>
    <p>نسبة تحقق المعيار: {{ $report->assessment_results['technology']['achievement_percentage'] }}%</p>

    <h4>أبرز النتائج:</h4>
    <ul>
        @foreach($report->assessment_results['technology']['notes'] as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>

    <h4>أبرز التوصيات:</h4>
    <ul>
        @foreach($report->assessment_results['technology']['recommendations'] as $rec)
            <li>{{ $rec }}</li>
        @endforeach
    </ul>
</div>

<div class="section">
    <h3>نتائج معيار الامتثال والجودة</h3>
    <p>نسبة تحقق المعيار: {{ $report->assessment_results['compliance_quality']['achievement_percentage'] }}%</p>

    <h4>أبرز النتائج:</h4>
    <ul>
        @foreach($report->assessment_results['compliance_quality']['notes'] as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>

    <h4>أبرز التوصيات:</h4>
    <ul>
        @foreach($report->assessment_results['compliance_quality']['recommendations'] as $rec)
            <li>{{ $rec }}</li>
        @endforeach
    </ul>
</div>

<div class="section">
    <h3>ملخص المخالفات</h3>
    <table class="violations-table">
        <thead>
        <tr>
            <th>وصف المخالفة</th>
            <th>المستوى</th>
            <th>التوصية</th>
        </tr>
        </thead>
        <tbody>
        @foreach($report->violations as $violation)
            <tr>
                <td>{{ $violation['description'] }}</td>
                <td>{{ $violation['level'] }}</td>
                <td>{{ $violation['recommendation'] }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>إجمالي المخالفات</td>
            <td colspan="2">{{ count($report->violations) }}</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="section">
    <h3>النتائج الإجمالية</h3>
    <table>
        <thead>
        <tr>
            <th>المعيار</th>
            <th>النسبة المحققة</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>التأسيس الإداري</td>
            <td>{{ $report->assessment_results['administrative_foundation']['achievement_percentage'] }}%</td>
        </tr>
        <tr>
            <td>القيادة</td>
            <td>{{ $report->assessment_results['leadership']['achievement_percentage'] }}%</td>
        </tr>
        <tr>
            <td>الموارد البشرية</td>
            <td>{{ $report->assessment_results['human_resources']['achievement_percentage'] }}%</td>
        </tr>
        <tr>
            <td>الخدمات</td>
            <td>{{ $report->assessment_results['services']['achievement_percentage'] }}%</td>
        </tr>
        <tr>
            <td>التقنية والرقمنة</td>
            <td>{{ $report->assessment_results['technology']['achievement_percentage'] }}%</td>
        </tr>
        <tr>
            <td>الامتثال والجودة</td>
            <td>{{ $report->assessment_results['compliance_quality']['achievement_percentage'] }}%</td>
        </tr>
        <tr class="total-row">
            <td>الإجمالي</td>
            <td>{{ $report->total_achievement_percentage }}%</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="section">
    <h3>التوصيات العامة</h3>
    <ul>
        @foreach($report->general_recommendations as $rec)
            <li>{{ $rec }}</li>
        @endforeach
    </ul>
</div>

<div class="section">
    <h3>الخطوات القادمة</h3>
    <ol>
        @foreach($report->next_steps as $step)
            <li>{{ $step }}</li>
        @endforeach
    </ol>
</div>

<div class="section">
    <h3>الخاتمة</h3>
    <p>{{ $report->conclusion }}</p>
</div>

<div class="section">
    <h3>معلومات المقيّم</h3>
    <table>
        <tr>
            <th>اسم المقيّم</th>
            <td>{{ $report->assessor_info['name'] }}</td>
        </tr>
        <tr>
            <th>التاريخ</th>
            <td>{{ $report->assessor_info['date'] }}</td>
        </tr>
        <tr>
            <th>التوقيع</th>
            <td>________________</td>
        </tr>
    </table>
</div>
</body>
</html>
