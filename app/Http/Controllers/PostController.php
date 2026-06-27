<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\PostStoreRequest;
use App\Http\Requests\Posts\PostUpdateRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = app(PostService::class)->list();

        return response()->json($posts);
    }

    public function show(Request $request): JsonResponse
    {
        $post = app(PostService::class)->findByUuid($request->route('uuid'));

        return response()->json($post);
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        $post = app(PostService::class)->create($request->validated());

        return response()->json($post, 201);
    }

    public function update(PostUpdateRequest $request): JsonResponse
    {
        $post = app(PostService::class)->update(
            $request->route('uuid'),
            $request->validated(),
        );

        return response()->json($post);
    }

    public function destroy(Request $request): JsonResponse
    {
        app(PostService::class)->delete($request->route('uuid'));

        return response()->json(null, 204);
    }
}
