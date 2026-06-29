<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Repository
{
    public function filter(
        array $filters,
        array $orderBy = [],
        bool $returnBuilder = false,
        int|false $limit = false,
    ): Collection|Builder {
        $query = $this->model->newQuery();

        if (method_exists($this->model, 'scopeFilter')) {
            $query->filter($filters);
        }

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        if ($limit !== false) {
            $query->limit($limit);
        }

        return $returnBuilder ? $query : $query->get();
    }

    public function create(array $data): Model
    {
        return $this->model->newQuery()->create($data);
    }

    public function find(string $uuid): ?Model
    {
        return $this->model->newQuery()->where('uuid', $uuid)->first();
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
}
