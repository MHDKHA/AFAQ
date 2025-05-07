<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقرير النهائي</title>
    <style>
        body {
            font-family: "Traditional Arabic", sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .header {
            text-align: center;
            padding: 10px;
            background-color: #f0f0f0;
        }
        .header img {
            max-width: 150px;
        }
        .page-title {
            text-align: center;
            font-size: 24px;
            margin: 20px 0;
        }
        .sub-title {
            text-align: center;
            font-size: 18px;
            margin: 10px 0;
        }
        .report-number {
            text-align: center;
            font-size: 14px;
            margin: 10px 0;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #004080;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .chart {
            width: 100%;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f0f0f0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .page-break {
            page-break-after: always;
        }
        ul {
            padding-right: 20px;
        }
        li {
            margin: 5px 0;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<!-- Cover Page -->
<div class="header">
    <img src="{{ public_path('assets/images/logo.png') }}" alt="آفاق الموارد">
    <h1>آفاق الموارد</h1>
    <p>www.afaqcm.com</p>
</div>

<div class="page-title">التقرير النهائي</div>
<div class="sub-title">لشركة {{ $clientName }}</div>
<div class="report-number">AFAQ-{{ date('y-m-d', strtotime($assessment->date)) }}-{{ $assessment->id }}</div>

<div class="page-break"></div>

<!-- Table of Contents -->
<div class="section">
    <div class="section-title">الفهارس</div>
    <table>
        <tr>
            <th>الموضوع</th>
            <th>الصفحة</th>
        </tr>
        <tr>
            <td>المقدمة</td>
            <td>4</td>
        </tr>
        <tr>
            <td>بيانات العميل</td>
            <td>5</td>
        </tr>
        <tr>
            <td>منهجية ومميزات التقييم</td>
            <td>6</td>
        </tr>
        <tr>
            <td>المرفقات</td>
            <td>7</td>
        </tr>
        <tr>
            <td>نتائج التقييم</td>
            <td>8</td>
        </tr>
        <tr>
            <td>معايير تقييم الجاهزية</td>
            <td>9</td>
        </tr>
        <tr>
            <td>تحليل بيئة المنظمة</td>
            <td>10</td>
        </tr>
        <tr>
            <td>لوحة النتائج العامة</td>
            <td>12</td>
        </tr>
        <tr>
            <td>نتائج معيار التأسيس الإداري</td>
            <td>13</td>
        </tr>
        <tr>
            <td>نتائج معيار القيادة</td>
            <td>14</td>
        </tr>
        <tr>
            <td>نتائج معيار التخطيط</td>
            <td>15</td>
        </tr>
        <tr>
            <td>نتائج معيار الموارد البشرية</td>
            <td>16</td>
        </tr>
        <tr>
            <td>نتائج معيار التشغيل</td>
            <td>17</td>
        </tr>
        <tr>
            <td>نتائج معيار تقييم الأداء</td>
            <td>18</td>
        </tr>
        <tr>
            <td>نتائج معيار التحسين</td>
            <td>19</td>
        </tr>
        <tr>
            <td>المخالفات المتوقعة</td>
            <td>20</td>
        </tr>
        <tr>
            <td>الملخص</td>
            <td>24</td>
        </tr>
        <tr>
            <td>ما بعد التقرير</td>
            <td>25</td>
        </tr>
        <tr>
            <td>اتصل بنا</td>
            <td>26</td>
        </tr>
    </table>
</div>

<div class="page-break"></div>

<!-- Introduction Letter -->
<div class="section">
    <div class="content">
        <p>سعادة الأستاذ {{ auth()->user()->name }}</p>
        <p>حفظه الله</p>
        <p>السلام عليكم ورحمة الله وبركاته وبعد</p>
        <p>يسعدني التقدم بالشكر لسعادتكم لإتاحة الفرصة لآفاق لتقديم خدمتها للتكامل معكم ومشاركتكم النجاح بإذن الله..</p>
        <p>وبناءً على الاجتماع الذي تم مع سعادتكم يوم {{ $assessment->date->format('l') }} {{ $assessment->date->format('d/m/Y') }}م وتحليل وضع الشركة فيسعدنا عرض التقرير التفصيلي بالملاحظات التي تم رصدها.</p>
        <p>ويوضح التقرير:</p>
        <ul>
            <li>الوضع الحالي ويمثل الملاحظات وفرص التحسين.</li>
            <li>المخالفات المتوقعة وقيمتها المالية.</li>
            <li>الإجراءات التصحيحية للعمل.</li>
        </ul>
        <p>آملين أن يكون هذا بداية تعاون مشترك مثمر بيننا بمشيئة الله.</p>
        <p>مع التقدير،،،</p>
    </div>
</div>

<div class="page-break"></div>

<!-- Client Data -->
<div class="section">
    <div class="section-title">بيانات العميل</div>
    <table>
        <tr>
            <td>اسم العميل</td>
            <td>{{ $clientName }}</td>
        </tr>
        <tr>
            <td>مجال العمل</td>
            <td>{{ $clientField }}</td>
        </tr>
        <tr>
            <td>فترات الدوام</td>
            <td>فترة واحدة</td>
        </tr>
        <tr>
            <td>عدد الموظفين رجال</td>
            <td>{{ $maleEmployees }}</td>
        </tr>
        <tr>
            <td>مواعيد الدوام</td>
            <td>مختلفة</td>
        </tr>
        <tr>
            <td>عدد الموظفات</td>
            <td>{{ $femaleEmployees }}</td>
        </tr>
        <tr>
            <td>مواعيد الراحة*</td>
            <td>لا يوجد</td>
        </tr>
        <tr>
            <td>عدد المتعاونين</td>
            <td>{{ $collaborators }}</td>
        </tr>
        <tr>
            <td>عدد الفروع</td>
            <td>{{ $branches }}</td>
        </tr>
        <tr>
            <td>أيام الراحة</td>
            <td>2</td>
        </tr>
    </table>
    <p>• ساعات العمل {{ $workingHours }} ساعات ولكن لا يوجد تعيين لساعات الدوام</p>
</div>

<div class="page-break"></div>

<!-- Methodology -->
<div class="section">
    <div class="section-title">منهجية ومميزات التقييم</div>
    <div class="content">
        <p>اعتمد التقييم على مقابلة مباشرة مع المعنيين في الشركة مع ملاحظة الآتي:</p>
        <ul>
            <li>أولاً: مقابلة المالك والمنفذين الفعليين للعمليات المباشرين لها.</li>
            <li>ثانياً: التأكد من توفر المعلومات المطلوبة لديهم.</li>
        </ul>

        <p>تم بناء أداتين للتقييم:</p>
        <ul>
            <li>الأولى لتقييم الجاهزية والتعرف على مستوى تطبيق النظام.</li>
            <li>الثانية لتقييم الموارد البشرية والتحقق من عدم وجود مخالفات.</li>
        </ul>

        <p>وتمت المقارنة بين الواقع الفعلي في الشركة مقارنة بمعيارين:</p>
        <ul>
            <li>معيار دولي في تأسيس الأعمال.</li>
            <li>معيار وطني مبني على قوانين وزارة الموارد البشرية.</li>
        </ul>
    </div>
</div>

<div class="page-break"></div>

<!-- Attachments -->
<div class="section">
    <div class="section-title">المرفقات</div>
    <div class="content">
        <p>نتج عن لقاء التقييم ملفين وهما:</p>

        <p><strong>أداة متابعة التصحيحات</strong> وهي أداة تقدمها مؤسسة آفاق لعملائها تساعدهم على:</p>
        <ul>
            <li>متابعة الإجراءات التصحيحية، والتحقق من تنفيذها.</li>
            <li>معرفة المتميزين من موظفيها في دعم التصحيحات.</li>
        </ul>
        <p>وتقدم بعد تسليم المشروع وإنهاء الإجراءات المالية.</p>

        <p><strong>التقرير النهائي</strong> لتقييم {{ $clientName }}، ويوضح:</p>
        <ul>
            <li>الوضع الحالي للشركة.</li>
            <li>فرص التحسين.</li>
            <li>المخالفات المتوقعة.</li>
            <li>والحلول التصحيحية المقترحة.</li>
        </ul>
    </div>
</div>

<div class="page-break"></div>

<!-- Assessment Results -->
<div class="section">
    <div class="section-title">نتائج التقييم</div>
    <div class="content">
        <p>تم تقييم الجاهزية في سبعة معايير وهي:</p>
        <ul>
            <li>التأسيس الإداري.</li>
            <li>القيادة.</li>
            <li>التخطيط.</li>
            <li>الموارد البشرية.</li>
            <li>التشغيل.</li>
            <li>تقييم الأداء.</li>
            <li>التحسين.</li>
        </ul>

        <div class="chart">
            <!-- This would be a chart in the actual implementation -->
            <table>
                <tr>
                    <th>المعيار</th>
                    <th>متوفر</th>
                    <th>غير متوفر</th>
                    <th>النسبة</th>
                </tr>
                @foreach($criteriaResults as $key => $result)
                    <tr>
                        <td>{{ $result['name'] }}</td>
                        <td>{{ $result['available'] }}</td>
                        <td>{{ $result['unavailable'] }}</td>
                        <td>{{ $result['percentage'] }}%</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="page-break"></div>

<!-- SWOT Analysis -->
<div class="section">
    <div class="section-title">تحليل بيئة المنظمة</div>

    <div class="section-title">أبرز نقاط القوة:</div>
    <ul>
        @foreach($strengthPoints as $point)
            <li>{{ $point }}</li>
        @endforeach
    </ul>

    <div class="section-title">أبرز نقاط الضعف:</div>
    <ul>
        @foreach($weaknessPoints as $point)
            <li>{{ $point }}</li>
        @endforeach
    </ul>

    <div class="section-title">أبرز فرص التحسين:</div>
    <ul>
        @foreach($opportunityPoints as $point)
            <li>{{ $point }}</li>
        @endforeach
    </ul>

    <div class="section-title">أبرز المخاطر:</div>
    <ul>
        @foreach($threatPoints as $point)
            <li>{{ $point }}</li>
        @endforeach
    </ul>
</div>

<div class="page-break"></div>

<!-- Results Dashboard -->
<div class="section">
    <div class="section-title">لوحة النتائج العامة</div>
    <div class="content">
        <p>يلاحظ الآتي:</p>
        <ul>
            <li>توجه الإدارة للتطوير يمثل نقطة القوة في الشركة.</li>
            <li>يوجد فرصة تحسين واضحة في كافة مناطق العمل التشغيلية بمشيئة الله.</li>
            <li>كذلك يوجد فرصة تحسين في العمليات المنهجية العليا المتعلقة بالتأسيس والتخطيط والقيادة والتقييم بمشيئة الله.</li>
        </ul>

        <div class="chart">
            <div class="chart">
                <img src="{{ $chartUrl }}"
                     alt="معايير التقييم"
                     style="width:100%; height:auto; display:block;">
            </div>
            <table>
                <tr>
                    <th>المعيار</th>
                    <th>متوفر</th>
                    <th>غير متوفر</th>
                    <th>لا ينطبق</th>
                </tr>
                @foreach($criteriaResults as $key => $result)
                    <tr>
                        <td>{{ $result['name'] }}</td>
                        <td>{{ $result['available'] }}</td>
                        <td>{{ $result['unavailable'] }}</td>
                        <td>{{ $result['na'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- Individual Criteria Results Pages -->
@foreach($criteriaResults as $key => $result)
    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">نتائج معيار {{ $result['name'] }}</div>
        <div class="content">
            <div class="chart">
                <!-- Chart for this criterion -->
                <table>
                    <tr>
                        <th>عدد المخالفات</th>
                        <th>نسبتها</th>
                    </tr>
                    <tr>
                        <td>{{ $result['unavailable'] }}</td>
                        <td>{{ number_format(($result['unavailable'] / ($result['available'] + $result['unavailable'] + 0.001)) * 100, 2) }}%</td>
                    </tr>
                </table>
            </div>

            <p class="text-center">{{ $result['percentage'] }}% نسبة تحقق المعيار</p>

            <div class="section-title">أبرز النتائج:</div>
            <p>سيتم تعبئة هذه البيانات بناءً على نتائج التقييم الفعلي لكل معيار.</p>

            <div class="section-title">أبرز التوصيات:</div>
            <p>سيتم تعبئة التوصيات بناءً على نتائج التقييم الفعلي لكل معيار.</p>
        </div>
    </div>
@endforeach

<div class="page-break"></div>

<!-- Violations -->
<div class="section">
    <div class="section-title">المخالفات المتوقعة</div>
    <div class="content">
        <p>بناء على ما سبق يتضح الآتي:</p>

        <table>
            <tr>
                <th>م</th>
                <th>المخالفة</th>
                <th>الغرامة</th>
                <th>الحل</th>
            </tr>
            @foreach($violations as $index => $violation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $violation['description'] }}</td>
                    <td>{{ number_format($violation['amount'], 0) }}</td>
                    <td>{{ $violation['solution'] }}</td>
                </tr>
            @endforeach
        </table>

        <p>إجمالي المخالفات: {{ number_format($totalViolationAmount, 0) }} {{ $adminName }}</p>
    </div>
</div>

<div class="page-break"></div>

<!-- Summary -->
<div class="section">
    <div class="section-title">الملخص</div>
    <div class="content">
        <p>{{ number_format($totalViolationAmount, 0) }} قيمة المخالفات المتوقعة</p>

        <p>يلاحظ ما يلي:</p>
        <ul>
            <li>معظم المخالفات تأتي من تفاصيل بسيطة يمكن تجاوزها.</li>
            <li>لا يوجد هيكل إداري بالمسئوليات والصلاحيات.</li>
            <li>معظم المخالفات يمكن تجاوزها بحلول بسيطة.</li>
        </ul>
    </div>
</div>

<div class="page-break"></div>

<!-- After Report -->
<div class="section">
    <div class="section-title">ما بعد التقرير</div>
    <div class="content">
        <p>يمكن تقديم العديد من الخدمات للشركة ومنها:</p>

        <table>
            <tr>
                <th>م</th>
                <th>الخدمة</th>
            </tr>
            <tr>
                <td>1</td>
                <td>إعادة الهيكلة الإدارية والمالية وإعداد سلم الرواتب.</td>
            </tr>
            <tr>
                <td>2</td>
                <td>تحليل النظام الإداري والتشغيلي.</td>
            </tr>
            <tr>
    <td>3</td>
    <td>تنفيذ عمليات التصحيح للموارد البشرية بما في ذلك مراجعة العقود.</td>
</tr>
<tr>
    <td>4</td>
    <td>تصميم وتنفيذ نظام للرقابة الداخلية.</td>
</tr>
<tr>
    <td>5</td>
    <td>تقديم خدمات استشارية دورية لمتابعة التطوير.</td>
</tr>
<tr>
    <td>6</td>
    <td>تقديم دورات تدريبية للموظفين في المجالات المختلفة.</td>
</tr>
</table>
</div>
</div>

<div class="page-break"></div>

<!-- Contact Us -->
<div class="section">
    <div class="section-title">اتصل بنا</div>
    <div class="content">
        <p class="text-center">للتواصل معنا والحصول على المزيد من المعلومات حول خدماتنا</p>
        <table>
            <tr>
                <td>رقم الهاتف</td>
                <td>+966 XX XXX XXXX</td>
            </tr>
            <tr>
                <td>البريد الإلكتروني</td>
                <td>info@afaqcm.com</td>
            </tr>
            <tr>
                <td>الموقع الإلكتروني</td>
                <td>www.afaqcm.com</td>
            </tr>
            <tr>
                <td>العنوان</td>
                <td>المملكة العربية السعودية - الرياض</td>
            </tr>
        </table>

        <p class="text-center">شكراً لثقتكم بنا</p>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>جميع الحقوق محفوظة © {{ date('Y') }} آفاق الموارد</p>
</div>
</body>
</html>
