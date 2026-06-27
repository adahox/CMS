<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('verified')->group(function () {
        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('posts/{uuid}', [PostController::class, 'show'])->name('posts.show');
        Route::put('posts/{uuid}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{uuid}', [PostController::class, 'destroy'])->name('posts.destroy');
    });
});
