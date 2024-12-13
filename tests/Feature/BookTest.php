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
     * Test order get books unprocessable.
     */
    public function testOrderGetBooksUnprocessable(): void
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
     * Test show a book.
     */
    public function testShowABook(): void
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
     * Test show a book not found.
     */
    public function testShowABookNotFound(): void
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
     * Test store a book.
     */
    public function testStoreABook(): void
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
     * Test store a book unprocessable.
     */
    public function testStoreABookUnprocessable(): void
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
     * Test update a book.
     */
    public function testUpdateABook(): void
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
     * Test update a book unprocessable.
     */
    public function testUpdateABookUnprocessable(): void
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
     * Test update a book not found.
     */
    public function testUpdateABookNotFound(): void
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
     * Test delete a book.
     */
    public function testDeleteABook(): void
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

    /**
     * Test get books by author id.
     */
    public function testGetBookByAuthorId(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book")
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
     * Test desc order get books by author id.
     */
    public function testDescGetBookByAuthorId(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book?order=DESC")
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
     * Test  order get books by author id unprocessable.
     */
    public function testOrderGetBooksByAuthorIdUnprocessable(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book?order=unprocessed")
            ->assertStatus(422);
    }

    /**
     * Test limit get books by author id.
     */
    public function testLimitGetBooksByAuthorId(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book?limit=5")
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
     * Test limit get books by author id unprocessable.
     */
    public function testLimitGetBooksByAuthorIdUnprocessable(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book?limit=unprocessed")
            ->assertStatus(422);
    }

    /**
     * Test page get books by author id.
     */
    public function testPageGetBooksByAuthorId(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book?page=3")
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
     * Test page get books by author id unprocessable.
     */
    public function testPageGetBooksByAuthorIdUnprocessable(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;
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
            ->getJson("api/authors/$authorId/book?page=unprocessed")
            ->assertStatus(422);
    }
}
