<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostPageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('posts/index');
    }

    public function create(): Response
    {
        return Inertia::render('posts/form');
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('posts/form', [
            'uuid' => $request->route('uuid'),
        ]);
    }
}
