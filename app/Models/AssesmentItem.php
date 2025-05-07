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
        'assessment_id',
        'criteria_id',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assesment::class);
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criteria_id');
    }
}
