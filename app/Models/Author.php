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

    public function scopeAuthorBooks($query)
    {
        return $query->with('books:id,author_id,title,description,publish_date');
    }
}
