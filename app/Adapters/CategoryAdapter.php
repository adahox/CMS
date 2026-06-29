<?php

namespace App\Adapters;

class CategoryAdapter extends AbstractAdapter
{
    public static function adapt(array $item): array
    {
        return [
            'uuid' => $item['uuid'],
            'name' => $item['name'],
        ];
    }
}
