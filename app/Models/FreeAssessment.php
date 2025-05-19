<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeAssessment extends Model
{
   protected $table = 'free_assessments';
    protected $fillable = [
        'registration_id',
        'responses',
    ];

    protected $casts = [
        'responses' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(UserRegistration::class, 'registration_id');
    }
}
