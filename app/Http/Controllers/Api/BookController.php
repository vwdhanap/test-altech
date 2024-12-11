<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Book\IndexRequest;
use App\Http\Requests\Api\Book\ShowRequest;
use App\Http\Requests\Api\Book\StoreRequest;
use App\Http\Requests\Api\Book\UpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Get books
     * 
     * @unauthenticated
     * 
     * @response BookResource
     */
    public function index(
        IndexRequest $request
    ): AnonymousResourceCollection {
        $books = Book::minimalBook()
            ->orderBy('created_at', $request->input('order', 'DESC'))
            ->paginate($request->input('limit', 10));

        return BookResource::collection($books);
    }

    /**
     * Show book
     * 
     * @unauthenticated
     * 
     * @response BookResource
     */
    public function show(
        $bookId,
        ShowRequest $request
    ): BookResource | JsonResponse {
        $cacheKey = "author.{$bookId}";

        $book = Cache::remember($cacheKey, $request->input('cache_duration', 3600), function () use ($bookId) {
            return Book::minimalBook()->find($bookId);
        });

        if (!$book) {
            return response()->json([
                'message' => 'The requested book was not found.'
            ]);
        }

        return new BookResource($book);
    }

    /**
     * Store book
     * 
     * @unauthenticated
     * 
     * @response BookResource
     */
    public function store(
        StoreRequest $request
    ): BookResource {
        $book = Book::create($request->only('author_id', 'title', 'description', 'publish_date'));

        return new BookResource($book);
    }

    /**
     * Update book
     * 
     * @unauthenticated
     * 
     * @response BookResource
     */
    public function update(
        $bookId,
        UpdateRequest $request
    ): BookResource | JsonResponse {
        $book = Book::minimalBook()->find($bookId);

        if (!$book) {
            return response()->json([
                'message' => 'The requested book was not found.'
            ]);
        }

        $book->update($request->only('author_id', 'title', 'description', 'publish_date'));

        return new BookResource($book);
    }

    /**
     * Delete author
     * 
     * @unauthenticated
     */
    public function destroy(
        $bookId,
    ): JsonResponse {
        Book::where('id', $bookId)->delete();

        return response()->json([
            'message' => 'Book has been deleted successfully'
        ]);
    }

    /**
     * Get books by author id
     * 
     * @unauthenticated
     * 
     * @response BookResource
     */
    public function getBooksByAuthorId(
        $authorId,
        IndexRequest $request
    ): AnonymousResourceCollection {
        $books = Book::where('author_id', $authorId)
            ->minimalBook()
            ->orderBy('created_at', $request->input('order', 'DESC'))
            ->paginate($request->input('limit', 10));

        return BookResource::collection($books);
    }
}
