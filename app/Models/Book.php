<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'publish_date'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function scopeMinimalBook($query)
    {
        return $query->select('id', 'author_id', 'title', 'description', 'publish_date');
    }
}
