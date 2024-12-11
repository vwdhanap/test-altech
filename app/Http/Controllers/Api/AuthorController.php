<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Author\IndexRequest;
use App\Http\Requests\Api\Author\ShowRequest;
use App\Http\Requests\Api\Author\StoreRequest;
use App\Http\Requests\Api\Author\UpdateRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    /**
     * Get authors
     * 
     * @unauthenticated
     * 
     * @response AuthorResource
     */
    public function index(
        IndexRequest $request
    ): AnonymousResourceCollection {
        $authors = Author::minimalAuthor()
            ->orderBy('created_at', $request->input('order', 'DESC'))
            ->paginate($request->input('limit', 10));

        return AuthorResource::collection($authors);
    }

    /**
     * Show author
     * 
     * @unauthenticated
     * 
     * @response AuthorResource
     */
    public function show(
        $authorId,
        ShowRequest $request
    ): AuthorResource | JsonResponse {
        $cacheKey = "author.{$authorId}";

        $author = Cache::remember($cacheKey, $request->input('cache_duration', 3600), function () use ($authorId) {
            return Author::minimalAuthor()->find($authorId);
        });

        if (!$author) {
            return response()->json([
                'message' => 'The requested author was not found.'
            ], 404);
        }

        return new AuthorResource($author);
    }

    /**
     * Store author
     * 
     * @unauthenticated
     * 
     * @response AuthorResource
     */
    public function store(
        StoreRequest $request
    ): AuthorResource {
        $author = Author::create($request->only('name', 'bio', 'birth_date'));

        return new AuthorResource($author);
    }

    /**
     * Update author
     * 
     * @unauthenticated
     * 
     * @response AuthorResource
     */
    public function update(
        $authorId,
        UpdateRequest $request
    ): AuthorResource | JsonResponse {
        $author = Author::minimalAuthor()->find($authorId);

        if (!$author) {
            return response()->json([
                'message' => 'The requested author was not found.'
            ], 404);
        }

        $author->update($request->only('name', 'bio', 'birth_date'));

        return new AuthorResource($author);
    }

    /**
     * Delete author
     * 
     * @unauthenticated
     */
    public function destroy(
        $authorId,
    ): JsonResponse {
        Author::where('id', $authorId)->delete();

        return response()->json([
            'message' => 'Author has been deleted successfully.'
        ]);
    }

    /**
     * Get books by author id
     * 
     * @unauthenticated
     * 
     * @response AuthorResource
     */
    public function getBooksByAuthorId(
        $authorId,
        ShowRequest $request
    ): AuthorResource | JsonResponse {
        $cacheKey = "author.{$authorId}.books";

        $author = Cache::remember($cacheKey, $request->input('cache_duration', 3600), function () use ($authorId) {
            return Author::minimalAuthor()
                ->authorBooks()
                ->find($authorId);
        });

        if (!$author) {
            return response()->json([
                'message' => 'The requested author was not found.'
            ]);
        }

        return new AuthorResource($author);
    }
}
