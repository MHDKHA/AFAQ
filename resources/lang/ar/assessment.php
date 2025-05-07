<?php

return [
    'navigation_label' => 'التقييمات',
    'model_label' => 'تقييم',
    'plural_model_label' => 'التقييمات',

    'form' => [
        'name' => 'اسم التقييم',
        'en_name' => 'اسم التقييم بالانجليزي',
        'date' => 'تاريخ التقييم',
        'description' => 'الوصف',
    ],

    'table' => [
        'name' => 'الاسم',
        'date' => 'التاريخ',
        'completion' => 'نسبة الإكمال',
        'available' => 'متوفر',
        'unavailable' => 'غير متوفر',
        'created_at' => 'تاريخ الإنشاء',
    ],

    'actions' => [
        'fill' => 'تعبئة التقييم',
        'view' => 'عرض النتائج',
        'back_to_assessments' => 'العودة إلى التقييمات',
        'available' => 'متوفر',
        'notes' => 'ملاحظات',
        'save' => 'حفظ التقييم',
        'save_success' => 'تم حفظ التقييم بنجاح',
        'save_error' => 'حدث خطأ أثناء حفظ التقييم',
    ],
    'view' => [
        'back_to_assessments' => 'العودة إلى التقييمات',
        'print_report' => 'طباعة التقرير',
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
];
