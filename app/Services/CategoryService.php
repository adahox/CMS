<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
    public function list(array $filter = []): Collection
    {
        return app(CategoryRepository::class)->filter($filter);
    }

    public function find(string $uuid): Category
    {
        $category = app(CategoryRepository::class)->find($uuid);

        if (! $category) {
            throw new NotFoundHttpException(__('Category not found.'));
        }

        return $category;
    }

    public function create(array $data): Category
    {
        return app(CategoryRepository::class)->create($data);
    }

    public function update(string $uuid, array $data): Category
    {
        $category = $this->find($uuid);

        return app(CategoryRepository::class)->update($category, $data);
    }

    public function delete(string $uuid): void
    {
        $category = $this->find($uuid);

        app(CategoryRepository::class)->delete($category);
    }
}
