<?php

namespace App\Domain\AdditionalFields\Repositories;

use App\Concerns\Repository;
use App\Domain\AdditionalFields\Models\AdditionalField;
use Illuminate\Database\Eloquent\Builder;

class AdditionalFieldRepository
{
    use Repository;

    public function __construct(private AdditionalField $model) {}

    protected function newQuery(array $criteria = []): Builder
    {
        $query = $this->model->newQuery()->with('rule');

        if (isset($criteria['target'])) {
            $target = $criteria['target'];
            unset($criteria['target']);

            $query->whereHas('rule', fn (Builder $ruleQuery) => $ruleQuery->where('target', $target));
        }

        foreach ($criteria as $column => $value) {
            $query->where($column, $value);
        }

        return $query;
    }
}
