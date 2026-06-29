<?php

namespace App\Domain\AdditionalFields\Repositories;

use App\Concerns\Repository;
use App\Domain\AdditionalFields\Models\AdditionalFieldValue;

class AdditionalFieldValueRepository
{
    use Repository;

    public function __construct(private AdditionalFieldValue $model) {}
}
