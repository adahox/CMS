<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function success(
        string $response,
        mixed $data = null,
        int $code = 200,
        mixed $paginate = false,
    ): JsonResponse {
        return response()->json([
            'status' => true,
            'response' => $response,
            'data' => $data,
            'paginate' => $paginate,
        ], $code);
    }

    protected function error(string $response, int $code = 400, mixed $data = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'response' => $response,
            'data' => $data,
            'paginate' => false,
        ], $code);
    }
}
