<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test default get books.
     */
    public function testDefaultGetBooks(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(50)->create(['author_id' => $author->id])->map(function ($book) {
            return [
                'id' => $book->id,
                'author_id' => $book->author_id,
                'title' => $book->title,
                'description' => $book->description,
                'publish_date' => $book->publish_date
            ];
        });

        $this
            ->getJson('api/books')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'author_id',
                        'title',
                        'description',
                        'publish_date'
                    ]
                ]
            ])
            ->assertJson([
                'data' => $books->sortBy('title')->take(10)->filter()->values()->toArray()
            ]);
    }

    /**
     * Test desc order get books.
     */
    public function testDescOrderGetBooks(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(50)->create(['author_id' => $author->id])->map(function ($book) {
            return [
                'id' => $book->id,
                'author_id' => $book->author_id,
                'title' => $book->title,
                'description' => $book->description,
                'publish_date' => $book->publish_date
            ];
        });

        $this
            ->getJson('api/books?order=DESC')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'author_id',
                        'title',
                        'description',
                        'publish_date'
                    ]
                ]
            ])
            ->assertJson([
                'data' => $books->sortByDesc('title')->take(10)->filter()->values()->toArray()
            ]);
    }

    /**
     * Test asc order get books unprocessable.
     */
    public function testAscOrderGetBooksUnprocessable(): void
    {
        $author = Author::factory()->create();
        Book::factory()->count(50)->create(['author_id' => $author->id])->map(function ($book) {
            return [
                'id' => $book->id,
                'author_id' => $book->author_id,
                'title' => $book->title,
                'description' => $book->description,
                'publish_date' => $book->publish_date
            ];
        });

        $this
            ->getJson('api/books?order=unprocessed')
            ->assertStatus(422);
    }

    /**
     * Test limit get books.
     */
    public function testLimitGetBooks(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(50)->create(['author_id' => $author->id])->map(function ($book) {
            return [
                'id' => $book->id,
                'author_id' => $book->author_id,
                'title' => $book->title,
                'description' => $book->description,
                'publish_date' => $book->publish_date
            ];
        });

        $this
            ->getJson('api/books?limit=5')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'author_id',
                        'title',
                        'description',
                        'publish_date'
                    ]
                ]
            ])
            ->assertJson([
                'data' => $books->sortBy('title')->take(5)->filter()->values()->toArray()
            ]);
    }
}
