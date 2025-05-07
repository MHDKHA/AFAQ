<?php

return [
    'navigation_label' => 'Assessments',
    'model_label' => 'Assessment',
    'plural_model_label' => 'Assessments',

    'form' => [
        'name' => 'Assessment Name',
        'en_name' => 'English Assessment Name',
        'date' => 'Assessment Date',
        'description' => 'Description',
    ],

    'table' => [
        'name' => 'Name',
        'date' => 'Date',
        'completion' => 'Completion Rate',
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'created_at' => 'Created At',
    ],

    'actions' => [
        'fill' => 'Fill Assessment',
        'view' => 'View Results',
        'back_to_assessments' => 'Back to Assessments',
        'available' => 'Available',
        'notes' => 'notes',
        'save' => 'Save',
        'save_success' => 'Saved successfully',
        'save_error' => 'Error saving Assessment',
    ],

    'fill' => [
        'back_to_assessments' => 'Back to Assessments',
        'available' => 'Available',
        'notes' => 'Notes',
        'save' => 'Save Assessment',
        'save_success' => 'Assessment saved successfully',
        'save_error' => 'Error saving assessment',
    ],
    'view' => [
        'back_to_assessments' => 'Back to Assessments',
        'print_report' => 'Print Report',
    ],
    'widgets' => [
        'domain' => 'Domain',
        'domain_selector_title' => 'Select Domain',
    ],
    'results' => [
        'order' => 'No',
        'main_criterion' => 'Main Criterion',
        'audit_question' => 'Audit Question',
        'available' => 'Available',
        'notes' => 'Notes',
    ],
    'stats' => [
        'assessment_name' => 'Assessment Name',
        'assessment_date' => 'Assessment Date: :date',
        'available_items' => 'Available Items',
        'unavailable_items' => 'Unavailable Items',
        'assessment_completion' => 'Assessment Completion',
        ':rate% of assessed items' => ':rate% of assessed items',
        ':rate% completed' => ':rate% completed',
    ],
];
