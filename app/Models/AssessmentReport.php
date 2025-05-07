<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class AssessmentReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'company_name',
        'stakeholder_name',
        'acknowledgment',
        'client_info',
        'attachments',
        'work_environment',
        'panel_notes',
        'assessment_results',
        'expected_violations',
        'total_fine',
        'follow_up_services',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'client_info' => 'array',
        'attachments' => 'array',
        'work_environment' => 'array',
        'panel_notes' => 'array',
        'assessment_results' => 'array',
        'expected_violations' => 'array',
        'follow_up_services' => 'array',
    ];

    /**
     * Create a new assessment report with default structure
     */
    public static function createWithDefaultStructure(): self
    {
        return new self([
            'client_info' => [
                'work_type' => '',
                'work_schedule' => '',
                'break_schedule' => '',
                'branches' => 0,
                'work_days' => 0,
                'day_off' => '',
                'male_emp' => 0,
                'female_emp' => 0,
                'collaborators' => 0,
            ],
            'work_environment' => [
                'pros' => [],
                'cons' => [],
                'improvement_areas' => [],
                'risk_areas' => [],
            ],
            'assessment_results' => [
                'administrative_foundation' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
                'leadership' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
                'planning' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
                'human_resources' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
                'operations' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
                'performance_evaluation' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
                'improvement' => [
                    'achievement_percentage' => 0,
                    'notes' => [],
                    'recommendations' => [],
                ],
            ],
        ]);
    }
}
