<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

// Rotas para criação de livros
Route::get('/books/create-id-number', [BookController::class, 'createWithId'])->name('books.create.id');
Route::post('/books/create-id-number', [BookController::class, 'storeWithId'])->name('books.store.id');

Route::get('/books/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
Route::post('/books/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('categories', CategoryController::class);
Route::resource('authors', AuthorController::class);
Route::resource('publishers', PublisherController::class);
Route::resource('books', BookController::class)->except(['create', 'store']);