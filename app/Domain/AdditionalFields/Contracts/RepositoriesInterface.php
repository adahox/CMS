<?php

namespace App\Domain\AdditionalFields\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoriesInterface
{
    public function filter(array $filters, array $orderBy = [], bool $returnBuilder = false, int|false $limit = false): Collection|Builder;

    public function create(array $data): Model;

    public function find(string $uuid): ?Model;

    public function update(Model $model, array $data): Model;

    public function delete(Model $model): void;
}
