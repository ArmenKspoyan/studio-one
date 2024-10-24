<?php

use App\Http\Controllers\BlogPostsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //TODO Profile Controller section
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //TODO Blog Posts Controller section

    Route::controller(BlogPostsController::class)->group(function () {
        Route::get('/blog-posts', 'index')->name('blog-posts');
        Route::get('/add-blog-post', 'create')->name('add-blog-post');
        Route::post('/add-blog-post', 'store')->name('store.blog-post');
        Route::delete('/delete-blog-post/{id}', 'destroy');
        Route::get('/get-blog-post', 'getById');
        Route::post('/update-blog-post', 'update');

    });
});

require __DIR__ . '/auth.php';
