<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdditionalFields\AdditionalFieldStoreRequest;
use App\Http\Requests\AdditionalFields\AdditionalFieldUpdateRequest;
use App\Services\AdditionalFieldService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdditionalFieldController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $fields = app(AdditionalFieldService::class)->list($request->all());

        return response()->json($fields);
    }

    public function show(Request $request): JsonResponse
    {
        $field = app(AdditionalFieldService::class)->find($request->route('uuid'));

        return response()->json($field);
    }

    public function store(AdditionalFieldStoreRequest $request): JsonResponse
    {
        $field = app(AdditionalFieldService::class)->create($request->validated());

        return response()->json($field, 201);
    }

    public function update(AdditionalFieldUpdateRequest $request): JsonResponse
    {
        $field = app(AdditionalFieldService::class)->update(
            $request->route('uuid'),
            $request->validated(),
        );

        return response()->json($field);
    }

    public function destroy(Request $request): JsonResponse
    {
        app(AdditionalFieldService::class)->delete($request->route('uuid'));

        return response()->json(null, 204);
    }
}
