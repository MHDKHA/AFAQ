
<?php

return [
    'navigation_label' => 'Assessment Tools',
    'model' => 'Tool',
    'plural' => 'Tools',
    'form' => [
        'name' => 'Name',
        'name_ar' => 'Arabic Name',
        'slug' => 'Slug',
        'description' => 'Description',
        'description_ar' => 'Arabic Description',
        'is_active' => 'Active',
        'role_name' => 'Required Role',
        'role_name_help' => 'If set, only users with this role can access this tool',
    ],
    'table' => [
        'name' => 'Name',
        'name_ar' => 'Arabic Name',
        'role_name' => 'Required Role',
        'is_active' => 'Active',
        'created_at' => 'Created',
    ],
];
