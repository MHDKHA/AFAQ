<?php

// app/Models/TemporaryAssessment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'session_id',
        'ip_address',
        'email',
        'name',
        'responses',
        'completed_at',
    ];

    protected $casts = [
        'responses' => 'array',
        'completed_at' => 'datetime',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
