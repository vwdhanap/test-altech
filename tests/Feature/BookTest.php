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

    /**
     * Test limit get books unprocessable.
     */
    public function testLimitGetBooksUnprocessable(): void
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
            ->getJson('api/books?limit=unprocessed')
            ->assertStatus(422);
    }

    /**
     * Test page get books.
     */
    public function testPageGetBooks(): void
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
            ->getJson('api/books?page=3')
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
            ->assertJsonPath('meta.current_page', 3);
    }

    /**
     * Test page get books unprocessable.
     */
    public function testPageGetBooksUnprocessable(): void
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
            ->getJson('api/books?page=unprocessed')
            ->assertStatus(422);
    }

    /**
     * Test show an book.
     */
    public function testShowAnBook(): void
    {
        $author = Author::factory()->create();
        $bookInitial = Book::factory()->create(['author_id' => $author->id]);
        $book = $bookInitial->toArray();
        $bookId = $bookInitial->id;
        unset($book['created_at']);
        unset($book['updated_at']);

        $this
            ->getJson("api/books/$bookId")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'author_id',
                    'title',
                    'description',
                    'publish_date'
                ]
            ])
            ->assertJson([
                'data' => $book
            ]);
    }

    /**
     * Test show an book not found.
     */
    public function testShowAnBookNotFound(): void
    {
        $author = Author::factory()->create();
        Book::factory()->create(['author_id' => $author->id]);

        $this
            ->getJson("api/books/1001")
            ->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'The requested book was not found.'
            ]);
    }

    /**
     * Test store an book.
     */
    public function testStoreAnBook(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->make(['author_id' => $author->id])->toArray();

        $this
            ->postJson('api/books', $book)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'author_id',
                    'title',
                    'description',
                    'publish_date'
                ]
            ])
            ->assertJson([
                'data' => $book
            ]);

        $this->assertDatabaseHas('books', $book);
    }

    /**
     * Test store an book unprocessable.
     */
    public function testStoreAnBookUnprocessable(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()
            ->make([
                'title' => fake()->sentence(300),
                'author_id' => $author->id
            ])
            ->toArray();

        $this
            ->postJson('api/books', $book)
            ->assertStatus(422);
    }

    /**
     * Test update an book.
     */
    public function testUpdateAnBook(): void
    {
        $author = Author::factory()->create();
        $initialBook = Book::factory()->create(['author_id' => $author->id]);
        $book = $initialBook->toArray();
        $bookId = $book['id'];
        unset($book['created_at']);
        unset($book['updated_at']);
        $newData = Book::factory()->make(['author_id' => $author->id])->toArray();

        $this
            ->putJson("api/books/$bookId", $newData)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'author_id',
                    'title',
                    'description',
                    'publish_date'
                ]
            ])
            ->assertJson([
                'data' => $newData
            ]);

        $this->assertDatabaseHas('books', $newData);
        $this->assertDatabaseMissing('books', $book);
    }

    /**
     * Test update an book unprocessable.
     */
    public function testUpdateAnBookUnprocessable(): void
    {
        $author = Author::factory()->create();
        $initialBook = Book::factory()->create(['author_id' => $author->id]);
        $book = $initialBook->toArray();
        $bookId = $book['id'];
        $newData = Book::factory()
            ->make([
                'title' => fake()->sentence(300),
                'author_id' => $author->id
            ])
            ->toArray();

        $this
            ->putJson("api/books/$bookId", $newData)
            ->assertStatus(422);
    }

    /**
     * Test update an book not found.
     */
    public function testUpdateAnBookNotFound(): void
    {
        $author = Author::factory()->create();
        Book::factory()->create(['author_id' => $author->id]);
        $newData = Book::factory()->make(['author_id' => $author->id])->toArray();

        $this
            ->putJson('api/books/1001', $newData)
            ->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'The requested book was not found.'
            ]);
    }

    /**
     * Test delete an book.
     */
    public function testDeleteAnBook(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);
        $bookId = $book->id;

        $this
            ->delete("api/books/$bookId")
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'Book has been deleted successfully.'
            ]);

        $this->assertModelMissing($book);
    }
}
