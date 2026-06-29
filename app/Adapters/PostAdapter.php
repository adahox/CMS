<?php

namespace App\Adapters;

class PostAdapter extends AbstractAdapter
{
    public static function adapt(mixed $item): array
    {
        return $item->toArray();
    }
}
