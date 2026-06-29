<?php

namespace App\Strategies\Standard;

use App\Adapters\CategoryAdapter;
use App\Http\Requests\Categories\CategoryStoreRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Interfaces\ServicesInterface;
use App\Repositories\CategoryRepository;
use App\Traits\InputValidations;
use Exception;

class Category implements ServicesInterface
{
    use InputValidations;

    public function __construct(private readonly CategoryRepository $repository) {}

    public function toList(array $filter = []): array
    {
        $data = $this->repository->filter($filter);

        return CategoryAdapter::adaptMany($data->toArray());
    }

    public function getByUuid(string $uuid): array
    {
        $category = $this->repository->find($uuid);

        if (! $category) {
            throw new Exception(__('Category not found.'));
        }

        return CategoryAdapter::adapt($category->toArray());
    }

    public function toCreate(array $data): array
    {
        $validated = $this->validate(new CategoryStoreRequest, $data);
        $category = $this->repository->create($validated);

        return CategoryAdapter::adapt($category->toArray());
    }

    public function updateByUuid(string $uuid, array $data): array
    {
        $validated = $this->validate(new CategoryUpdateRequest, array_merge($data, ['uuid' => $uuid]));
        $category = $this->repository->find($uuid);

        if (! $category) {
            throw new Exception(__('Category not found.'));
        }

        unset($validated['uuid']);

        $category = $this->repository->update($category, $validated);

        return CategoryAdapter::adapt($category->toArray());
    }

    public function deleteByUuid(string $uuid): void
    {
        $category = $this->repository->find($uuid);

        if (! $category) {
            throw new Exception(__('Category not found.'));
        }

        $this->repository->delete($category);
    }
}
