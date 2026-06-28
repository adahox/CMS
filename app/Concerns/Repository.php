<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Repository
{
    public function filter(array $criteria = []): Collection
    {
        return $this->newQuery($criteria)->get();
    }

    public function create(array $data): Model
    {
        return $this->model->newQuery()->create($data);
    }

    public function find(string $uuid): ?Model
    {
        return $this->newQuery()->where('uuid', $uuid)->first();
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model->refresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    protected function newQuery(array $criteria = []): Builder
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $column => $value) {
            $query->where($column, $value);
        }

        return $query;
    }
}
