<?php

namespace App\Http\Controllers;

use App\Strategies\Standard\Category as CategoryStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryStrategy $category) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->category->toList($request->query());

            return $this->success(
                response: 'Listagem gerada com sucesso.',
                data: $data,
            );
        } catch (\Exception $e) {
            return $this->error(response: $e->getMessage());
        }
    }

    public function show(Request $request): JsonResponse
    {
        try {
            $data = $this->category->getByUuid($request->route('uuid'));

            return $this->success(
                response: 'Registro encontrado com sucesso.',
                data: $data,
            );
        } catch (\Exception $e) {
            return $this->error(response: $e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->category->toCreate($request->all());

            return $this->success(
                response: 'Registro criado com sucesso.',
                data: $data,
                code: 201,
            );
        } catch (\Exception $e) {
            return $this->error(response: $e->getMessage());
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $data = $this->category->updateByUuid($request->route('uuid'), $request->all());

            return $this->success(
                response: 'Registro atualizado com sucesso.',
                data: $data,
            );
        } catch (\Exception $e) {
            return $this->error(response: $e->getMessage());
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $this->category->deleteByUuid($request->route('uuid'));

            return $this->success(
                response: 'Registro removido com sucesso.',
                data: null,
            );
        } catch (\Exception $e) {
            return $this->error(response: $e->getMessage());
        }
    }
}
