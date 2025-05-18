<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Spatie\Translatable\HasTranslations;

class AssessmentReport extends Model
{
    use HasFactory;

    protected $table = 'assessment_reports';

    protected $fillable = [
        'assesment_id',
        'company_id',
        'acknowledgment',
        'attachments',
        'work_environment',
        'panel_notes',
        'assessment_results',
        'expected_violations',
        'total_fine',
        'follow_up_services',
    ];

    protected $casts = [
        'attachments' => 'array',
        'work_environment' => 'array',
        'panel_notes' => 'array',
        'assessment_results' => 'array',
        'expected_violations' => 'array',
        'follow_up_services' => 'array',
        'total_fine' => 'decimal:2',
    ];

    public function assessment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assesment_id');
    }

    /**
     * Create a new assessment report with default structure
     */
    public static function createWithDefaultStructure(): self
    {
        return new self([
            'client_info' => [
                'work_type'        => '',
                'work_schedule'    => '',
                'break_schedule'   => '',
                'branches'         => 0,
                'work_days'        => 0,
                'day_off'          => '',
                'male_emp'         => 0,
                'female_emp'       => 0,
                'collaborators'    => 0,
            ],
            'attachments' => [],
            'work_environment' => [
                'pros'             => [],
                'cons'             => [],
                'improvement_areas'=> [],
                'risk_areas'       => [],
            ],
            'panel_notes' => [],
            'assessment_results' => [
                'administrative_foundation' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
                'leadership' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
                'planning' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
                'human_resources' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
                'operations' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
                'performance_evaluation' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
                'improvement' => [
                    'achievement_percentage' => 0,
                    'notes'                  => [],
                    'recommendations'        => [],
                ],
            ],
            'expected_violations' => [],
            'follow_up_services'  => [],
            'total_fine'          => 0,
        ]);
    }

}
