<?php
// app/Models/Tool.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'slug',
        'is_active',
        'role_name', // The role name that will be allowed to access this tool
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }
}
