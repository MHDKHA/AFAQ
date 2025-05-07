<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'domain_id')->orderBy('order');
    }

    public function criteria()
    {
        return $this->hasManyThrough(Criterion::class, Category::class);
    }
}
