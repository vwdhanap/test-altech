<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('authors', AuthorController::class)->only('store', 'index', 'show', 'update', 'destroy');
Route::get('authors/{authorId}/book', [AuthorController::class, 'getBooksByAuthorId']);
Route::resource('books', BookController::class)->only('store', 'index', 'show', 'update', 'destroy');
