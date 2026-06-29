<?php

namespace App\Adapters;

abstract class AbstractAdapter
{
    abstract public static function adapt(array $item): array;

    public static function adaptMany(iterable $items): array
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = static::adapt($item);
        }

        return $result;
    }
}
