<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';

    protected $fillable = [
        'name',
        'name_ar',
        'date',
        'description',
        'user_id',
        'company_id',
    ];
    protected $casts = [
        'date' => 'date',
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(AssesmentItem::class, 'assesment_id', 'id');
    }

    public function report()
    {
        return $this->hasOne(AssessmentReport::class, 'assesment_id', 'id');
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
