<?php

use App\Http\Controllers\AdditionalFieldController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('verified')->group(function () {

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{uuid}', [CategoryController::class, 'show'])->name('categories.show');
        Route::put('categories/{uuid}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{uuid}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('posts/{uuid}', [PostController::class, 'show'])->name('posts.show');
        Route::put('posts/{uuid}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{uuid}', [PostController::class, 'destroy'])->name('posts.destroy');

        Route::get('additional-fields', [AdditionalFieldController::class, 'index'])->name('additional-fields.index');
        Route::post('additional-fields', [AdditionalFieldController::class, 'store'])->name('additional-fields.store');
        Route::get('additional-fields/{uuid}', [AdditionalFieldController::class, 'show'])->name('additional-fields.show');
        Route::put('additional-fields/{uuid}', [AdditionalFieldController::class, 'update'])->name('additional-fields.update');
        Route::delete('additional-fields/{uuid}', [AdditionalFieldController::class, 'destroy'])->name('additional-fields.destroy');
    });
});
