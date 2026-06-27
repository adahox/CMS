<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $result = app(AuthService::class)->login($request->validated());

        return response()->json($result);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = app(AuthService::class)->register($request->validated());

        return response()->json($result, 201);
    }

    public function logout(Request $request): JsonResponse
    {
        app(AuthService::class)->logout($request->user());

        return response()->json(null, 204);
    }
}
