<?php

namespace App\Adapters;

use App\Domain\AdditionalFields\Models\AdditionalField;

class AdditionalFieldAdapter extends AbstractAdapter
{
    public static function adapt(mixed $item): array
    {
        return $item->toArray();
    }
}
