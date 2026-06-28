<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CategoryStoreRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = app(CategoryService::class)->list($request->all());

        return response()->json($categories);
    }

    public function show(Request $request): JsonResponse
    {
        $category = app(CategoryService::class)->find($request->route('uuid'));

        return response()->json($category);
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = app(CategoryService::class)->create($request->validated());

        return response()->json($category, 201);
    }

    public function update(CategoryUpdateRequest $request): JsonResponse
    {
        $category = app(CategoryService::class)->update(
            $request->route('uuid'),
            $request->validated(),
        );

        return response()->json($category);
    }

    public function destroy(Request $request): JsonResponse
    {
        app(CategoryService::class)->delete($request->route('uuid'));

        return response()->json(null, 204);
    }
}
