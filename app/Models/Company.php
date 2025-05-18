<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'stakeholder_name',
        'stakeholder_name_ar',
        'client_info',
        'address',
        'phone',
        'website',
        'description',
    ];

    protected $casts = [
        'client_info' => 'array',
    ];

    public function userDetails(): HasMany
    {
        return $this->hasMany(UserDetail::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assesment::class);
    }
}
