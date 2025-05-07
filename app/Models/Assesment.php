<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assesment extends Model
{
    use HasFactory;

    protected $table = 'assessments';
    protected $fillable = [
        'name',
        'en_name',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(AssesmentItem::class, 'assessment_id');
    }

    public function getCompletionPercentageAttribute(): float|int
    {
        $totalCriteria = Criterion::count();


        $answeredCriteria = $this?->items()?->count() ;

        if ($totalCriteria === 0) {
            return 0;
        }

        return round(($answeredCriteria / $totalCriteria) * 100);
    }

    public function getAvailableCountAttribute(): int
    {
        return $this->items()->where('is_available', true)->count();
    }

    public function getUnavailableCountAttribute(): int
    {
        return $this->items()->where('is_available', false)->count();
    }
}
