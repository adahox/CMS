<?php

use App\Http\Controllers\Web\PostPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('dashboard'))->name('dashboard');

    Route::get('/posts', [PostPageController::class, 'index'])->name('posts.page.index');
    Route::get('/posts/create', [PostPageController::class, 'create'])->name('posts.page.create');
    Route::get('/posts/{uuid}/edit', [PostPageController::class, 'edit'])->name('posts.page.edit');
});
