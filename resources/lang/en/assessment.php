<?php

return [
    'navigation_label' => 'Assessments',
    'model_label' => 'Assessment',
    'plural_model_label' => 'Assessments',

    'form' => [
        'name' => 'Assessment Name',
        'name_ar' => 'Arabic Assessment Name',
        'user' => 'Assessor',
        'company' => 'Company',
        'date' => 'Assessment Date',
        'description' => 'Description',
    ],

    'table' => [
        'name' => 'Name',
        'company' => 'Company',
        'user' => 'Assessor',
        'date' => 'Date',
        'completion' => 'Completion Rate',
        'available' => 'Available',
        'unavailable' => 'Unavailable',
        'has_report' => 'Has Report',
        'created_at' => 'Created At',
    ],

    'filters' => [
        'company' => 'Filter by Company',
        'user' => 'Filter by Assessor',
    ],

    'actions' => [
        'fill' => 'Fill Assessment',
        'view' => 'View Results',
        'create_report' => 'Create Report',
        'view_report' => 'View Report',
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
        'questions' => 'Questions/Criteria',
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

    'report_title' => 'Assessment Report',
    'client_info' => [
        'work_type' => 'Type of Business',
        'work_schedule' => 'Work Schedule',
        'break_schedule' => 'Break Schedule',
        'branches' => 'Number of Branches',
        'work_days' => 'Working Days',
        'day_off' => 'Days Off',
        'male_emp' => 'Number of Employees (Male)',
        'female_emp' => 'Number of Employees (Female)',
        'collaborators' => 'Collaborators',
    ],
    'work_environment' => [
        'strengths' => 'Strengths',
        'weaknesses' => 'Weaknesses',
        'improvement_areas' => 'Improvement Areas',
        'risk_areas' => 'Risk Areas',
    ],
    'sections' => [
        'general_results' => 'General Results Panel',
        'observations' => 'Observations',
        'administrative_foundation' => 'Administrative Foundation Criterion Results',
        'leadership' => 'Leadership Criterion Results',
        'planning' => 'Planning Criterion Results',
        'human_resources' => 'Human Resources Criterion Results',
        'operations' => 'Operations Criterion Results',
        'performance_evaluation' => 'Performance Evaluation Criterion Results',
        'improvement' => 'Improvement Criterion Results',
    ],
    'common' => [
        'key_results' => 'Key Results',
        'key_recommendations' => 'Key Recommendations',
        'achievement_percentage' => 'Achievement Percentage',
        'total' => 'Total',
    ],
    'violations' => [
        'title' => 'Expected Violations',
        'violation' => 'Violation',
        'fine' => 'Fine (SAR)',
        'solution' => 'Solution',
        'total_fines' => 'Total Fines',
    ],
    'post_report' => [
        'title' => 'Post-Report Services',
        'description' => 'We can provide many services to the company, including:',
        'service' => 'Service',
    ],
    'assessor_info' => [
        'title' => 'Assessor Information',
        'prepared_on' => 'This report was prepared on:',
    ],
    'footer' => 'All Rights Reserved',
];
