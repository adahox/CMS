<?php

namespace App\Domain\AdditionalFields\Repositories;

use App\Concerns\Repository;
use App\Domain\AdditionalFields\Models\AdditionalFieldRule;

class AdditionalFieldRuleRepository
{
    use Repository;

    public function __construct(private AdditionalFieldRule $model) {}
}
