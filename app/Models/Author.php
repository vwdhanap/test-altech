<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'birth_date'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function scopeMinimalAuthor($query)
    {
        return $query->select('id', 'name', 'bio', 'birth_date');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->minimalAuthor()->where($field ?? 'id', $value)->firstOrFail();
    }
}
