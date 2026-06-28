<?php

namespace App\Repositories;

use App\Concerns\Repository;
use App\Models\AdditionalField;

class AdditionalFieldRepository
{
    use Repository;

    public function __construct(private AdditionalField $model) {}
}
