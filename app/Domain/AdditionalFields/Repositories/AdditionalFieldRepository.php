<?php

namespace App\Domain\AdditionalFields\Repositories;

use App\Domain\AdditionalFields\Concerns\Repository;
use App\Domain\AdditionalFields\Contracts\RepositoriesInterface;
use App\Domain\AdditionalFields\Models\AdditionalField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdditionalFieldRepository implements RepositoriesInterface
{
    use Repository {
        create as traitCreate;
        find as traitFind;
        update as traitUpdate;
        delete as traitDelete;
    }

    public function __construct(public AdditionalField $model) {}

    public function filter(
        array $filters,
        array $orderBy = [],
        bool $returnBuilder = false,
        int|false $limit = false,
    ): Collection|Builder {
        $query = $this->model->newQuery()->with('rule');

        if (isset($filters['target'])) {
            $target = $filters['target'];
            unset($filters['target']);

            $query->whereHas('rule', fn (Builder $ruleQuery) => $ruleQuery->where('target', $target));
        }

        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        foreach ($orderBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        if ($limit !== false) {
            $query->limit($limit);
        }

        return $returnBuilder ? $query : $query->get();
    }
}
