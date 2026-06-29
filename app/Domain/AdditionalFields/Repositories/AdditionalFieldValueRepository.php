<?php

namespace App\Domain\AdditionalFields\Repositories;

use App\Domain\AdditionalFields\Concerns\Repository;
use App\Domain\AdditionalFields\Models\AdditionalFieldValue;

class AdditionalFieldValueRepository
{
    use Repository;

    public function __construct(public AdditionalFieldValue $model) {}
}
