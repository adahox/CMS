<?php

namespace App\Scopes;

trait CategoryScopes
{
    private array $filters = [
        'name',
    ];

    protected function createFilter($query, array $filters)
    {
        foreach ($this->filters as $field) {
            if (! array_key_exists($field, $filters) || $filters[$field] === '' || $filters[$field] === null) {
                continue;
            }

            if ($field === 'name') {
                $query->where($field, 'like', '%'.$filters[$field].'%');

                continue;
            }

            $query->where($field, $filters[$field]);
        }

        return $query;
    }
}
