<?php

namespace App\Domain\AdditionalFields\Repositories;

use App\Domain\AdditionalFields\Concerns\Repository;
use App\Domain\AdditionalFields\Models\AdditionalFieldRule;

class AdditionalFieldRuleRepository
{
    use Repository;

    public function __construct(public AdditionalFieldRule $model) {}
}
