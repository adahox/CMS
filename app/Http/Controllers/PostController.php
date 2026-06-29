<?php

namespace App\Http\Controllers;

use App\Strategies\Standard\Post as PostStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private readonly PostStrategy $post) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->post->toList($request->query());

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
            $data = $this->post->getByUuid($request->route('uuid'));

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
            $data = $this->post->toCreate($request->all());

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
            $data = $this->post->updateByUuid($request->route('uuid'), $request->all());

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
            $this->post->deleteByUuid($request->route('uuid'));

            return $this->success(
                response: 'Registro removido com sucesso.',
                data: null,
            );
        } catch (\Exception $e) {
            return $this->error(response: $e->getMessage());
        }
    }
}
