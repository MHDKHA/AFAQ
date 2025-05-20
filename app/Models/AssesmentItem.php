<?php
// app/Models/AssesmentItem.php - ensure correct column name mapping
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssesmentItem extends Model
{
    use HasFactory;

    protected $table = 'assessment_items';

    protected $fillable = [
        'assessment_id', // Make sure this matches the column name in the database
        'criteria_id',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criteria_id');
    }
}
