<?php

return [
    'navigation_label' => 'التقييمات',
    'model_label' => 'تقييم',
    'plural_model_label' => 'التقييمات',




    'form' => [
        'name' => 'اسم التقييم',
        'name_ar' => 'اسم التقييم بالعربية',
        'user' => 'المقيّم',
        'company' => 'الشركة',
        'date' => 'تاريخ التقييم',
        'description' => 'الوصف',
    ],

    'table' => [
        'name' => 'الاسم',
        'company' => 'الشركة',
        'user' => 'المقيّم',
        'date' => 'التاريخ',
        'completion' => 'نسبة الإكمال',
        'available' => 'متوفر',
        'unavailable' => 'غير متوفر',
        'has_report' => 'لديه تقرير',
        'created_at' => 'تاريخ الإنشاء',
    ],

    'filters' => [
        'company' => 'تصفية حسب الشركة',
        'user' => 'تصفية حسب المقيّم',
    ],

    'actions' => [
        'fill' => 'تعبئة التقييم',
        'view' => 'عرض النتائج',
        'create_report' => 'إنشاء تقرير',
        'view_report' => 'عرض التقرير',
        'back_to_assessments' => 'العودة إلى التقييمات',
        'available' => 'متوفر',
        'notes' => 'ملاحظات',
        'save' => 'حفظ التقييم',
        'save_success' => 'تم حفظ التقييم بنجاح',
        'save_error' => 'حدث خطأ أثناء حفظ التقييم',
    ],
    'fill' => [
        'back_to_assessments' => 'العودة إلى التقييمات',
        'available' => 'متوفر',
        'notes' => 'ملاحضات',
        'save' => 'حفظ التقييم',
        'save_success' => 'تم الحفظ بنجاح',
        'save_error' => 'توجد مشكله عند الحفظ',
    ],
    'view' => [
        'back_to_assessments' => 'العودة إلى التقييمات',
        'print_report' => 'طباعة التقرير',
        'questions' => 'المعايير / السؤال',
    ],
    'widgets' => [
        'domain' => 'المجال',
        'domain_selector_title' => 'تحديد المجال',
    ],
    'results' => [
        'order' => 'م',
        'main_criterion' => 'العنصر الرئيسي',
        'audit_question' => 'السؤال التدقيقي',
        'available' => 'متوفر',
        'notes' => 'ملاحظات',
    ],
    'stats' => [
        'assessment_name' => 'اسم التقييم',
        'assessment_date' => 'تاريخ التقييم: :date',
        'available_items' => 'العناصر المتوفرة',
        'unavailable_items' => 'العناصر غير المتوفرة',
        'assessment_completion' => 'اكتمال التقييم',
        ':rate% of assessed items' => ':rate% من العناصر المقيمة',
        ':rate% completed' => ':rate% مكتمل',
    ],

    'report_title' => 'تقرير التقييم',
    'client_info' => [
        'work_type'     => 'نوع العمل',
        'work_schedule' => 'جدول العمل',
        'break_schedule'=> 'جدول الاستراحة',
        'branches'      => 'عدد الفروع',
        'work_days'     => 'أيام العمل',
        'day_off'       => 'أيام الإجازة',
        'male_emp'      => 'عدد الموظفين (ذكور)',
        'female_emp'    => 'عدد الموظفات (إناث)',
        'collaborators' => 'المتعاونين',
    ],
    'work_environment' => [
        'strengths'        => 'نقاط القوة',
        'weaknesses'       => 'نقاط الضعف',
        'improvement_areas'=> 'مجالات التحسين',
        'risk_areas'       => 'مجالات المخاطر',
    ],
    'sections' => [
        'general_results'             => 'لوحة النتائج العامة',
        'observations'                => 'الملاحظات',
        'administrative_foundation'   => 'نتائج معيار الأساس الإداري',
        'leadership'                  => 'نتائج معيار القيادة',
        'planning'                    => 'نتائج معيار التخطيط',
        'human_resources'             => 'نتائج معيار الموارد البشرية',
        'operations'                  => 'نتائج معيار العمليات',
        'performance_evaluation'      => 'نتائج معيار تقييم الأداء',
        'improvement'                 => 'نتائج معيار التحسين',
    ],
    'common' => [
        'key_results'          => 'النتائج الرئيسية',
        'key_recommendations'  => 'التوصيات الرئيسية',
        'achievement_percentage'=> 'نسبة التحقيق',
        'total'                => 'الإجمالي',
    ],
    'violations' => [
        'title'       => 'المخالفات المتوقعة',
        'violation'   => 'المخالفة',
        'fine'        => 'الغرامة (ر.س)',
        'solution'    => 'الحل',
        'total_fines' => 'إجمالي الغرامات',
    ],
    'post_report' => [
        'title'       => 'خدمات ما بعد التقرير',
        'description' => 'نستطيع تقديم العديد من الخدمات للشركة، بما في ذلك:',
        'service'     => 'الخدمة',
    ],
    'assessor_info' => [
        'title'       => 'معلومات المُقَيِّم',
        'prepared_on' => 'تم إعداد هذا التقرير بتاريخ:',
    ],
    'footer' => 'جميع الحقوق محفوظة',
];
