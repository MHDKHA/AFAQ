<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssesmentItem extends Model
{
    use HasFactory;

    protected $table = 'assessment_items';

    protected $fillable = [
        'assesment_id',
        'criteria_id',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function assesment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assesment_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criteria_id');
    }
}

