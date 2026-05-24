<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('blogs.index');
});

Route::get('blog/{slug}', [BlogController::class, 'publicShow'])->name('blog.public-show');
Route::match(['post', 'put'], 'blogs/preview', [BlogController::class, 'preview'])->name('blogs.preview.live');
Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload-image');
Route::get('blogs/{blog}/preview', [BlogController::class, 'livePreview'])->name('blogs.preview');
Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);
Route::resource('blogs', BlogController::class);
