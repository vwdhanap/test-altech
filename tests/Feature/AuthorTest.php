<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test default get authors.
     */
    public function testDefaultGetAuthors(): void
    {
        $authors = Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'bio',
                        'birth_date'
                    ]
                ]
            ])
            ->assertJson([
                'data' => $authors->sortBy('name')->take(10)->filter()->values()->toArray()
            ]);
    }

    /**
     * Test desc order get authors.
     */
    public function testDescOrderGetAuthors(): void
    {
        $authors = Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors?order=DESC')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'bio',
                        'birth_date'
                    ]
                ]
            ])
            ->assertJson([
                'data' => $authors->sortByDesc('name')->take(10)->filter()->values()->toArray()
            ]);
    }

    /**
     * Test asc order get authors unprocessable.
     */
    public function testAscOrderGetAuthorsUnprocessable(): void
    {
        Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors?order=unprocessed')
            ->assertStatus(422);
    }

    /**
     * Test limit get authors.
     */
    public function testLimitGetAuthors(): void
    {
        $authors = Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors?limit=5')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'bio',
                        'birth_date'
                    ]
                ]
            ])
            ->assertJson([
                'data' => $authors->sortBy('name')->take(5)->filter()->values()->toArray()
            ]);
    }

    /**
     * Test limit get authors unprocessable.
     */
    public function testLimitGetAuthorsUnprocessable(): void
    {
        Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors?limit=unprocessed')
            ->assertStatus(422);
    }

    /**
     * Test page get authors.
     */
    public function testPageGetAuthors(): void
    {
        Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors?page=3')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'bio',
                        'birth_date'
                    ]
                ]
            ])
            ->assertJsonPath('meta.current_page', 3);
    }

    /**
     * Test page get authors unprocessable.
     */
    public function testPageGetAuthorsUnprocessable(): void
    {
        Author::factory()->count(50)->create()->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'birth_date' => $author->birth_date,
            ];
        });

        $this
            ->getJson('api/authors?page=unprocessed')
            ->assertStatus(422);
    }

    /**
     * Test show an author.
     */
    public function testShowAnAuthor(): void
    {
        $authorInitial = Author::factory()->create();
        $author = $authorInitial->toArray();
        $authorId = $authorInitial->id;
        unset($author['created_at']);
        unset($author['updated_at']);

        $this
            ->getJson("api/authors/$authorId")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'bio',
                    'birth_date'
                ]
            ])
            ->assertJson([
                'data' => $author
            ]);
    }

    /**
     * Test show an author not found.
     */
    public function testShowAnAuthorNotFound(): void
    {
        Author::factory()->create();

        $this
            ->getJson("api/authors/1001")
            ->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'The requested author was not found.'
            ]);
    }

    /**
     * Test store an author.
     */
    public function testStoreAnAuthor(): void
    {
        $author = Author::factory()
            ->make()
            ->toArray();

        $this
            ->postJson('api/authors', $author)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'bio',
                    'birth_date'
                ]
            ])
            ->assertJson([
                'data' => $author
            ]);

        $this->assertDatabaseHas('authors', $author);
    }

    /**
     * Test store an author unprocessable.
     */
    public function testStoreAnAuthorUnprocessable(): void
    {
        $author = Author::factory()
            ->make([
                'name' => fake()->sentence(300)
            ])
            ->toArray();

        $this
            ->postJson('api/authors', $author)
            ->assertStatus(422);
    }

    /**
     * Test update an author.
     */
    public function testUpdateAnAuthor(): void
    {
        $initialAuthor = Author::factory()->create();
        $author = $initialAuthor->toArray();
        $authorId = $author['id'];
        unset($author['created_at']);
        unset($author['updated_at']);
        $newData = Author::factory()
            ->make()
            ->toArray();

        $this
            ->putJson("api/authors/$authorId", $newData)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'bio',
                    'birth_date'
                ]
            ])
            ->assertJson([
                'data' => $newData
            ]);

        $this->assertDatabaseHas('authors', $newData);
        $this->assertDatabaseMissing('authors', $author);
    }

    /**
     * Test update an author unprocessable.
     */
    public function testUpdateAnAuthorUnprocessable(): void
    {
        Author::factory()->create();
        $newData = Author::factory()
            ->make([
                'name' => fake()->sentence(300)
            ])
            ->toArray();

        $this
            ->putJson('api/authors/$authorId', $newData)
            ->assertStatus(422);
    }

    /**
     * Test update an author not found.
     */
    public function testUpdateAnAuthorNotFound(): void
    {
        Author::factory()->create();
        $newData = Author::factory()
            ->make()
            ->toArray();

        $this
            ->putJson('api/authors/1001', $newData)
            ->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'The requested author was not found.'
            ]);
    }

    /**
     * Test delete an author.
     */
    public function testDeleteAnAuthor(): void
    {
        $author = Author::factory()->create();
        $authorId = $author->id;

        $this
            ->delete("api/authors/$authorId")
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ])
            ->assertJson([
                'message' => 'Author has been deleted successfully.'
            ]);

        $this->assertModelMissing($author);
    }
}
