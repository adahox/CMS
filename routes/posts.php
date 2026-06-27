<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{uuid}', [PostController::class, 'show'])->name('posts.show');
    Route::put('posts/{uuid}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{uuid}', [PostController::class, 'destroy'])->name('posts.destroy');
});
