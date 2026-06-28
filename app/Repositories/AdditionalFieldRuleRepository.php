<?php

namespace App\Repositories;

use App\Concerns\Repository;
use App\Models\AdditionalFieldRule;

class AdditionalFieldRuleRepository
{
    use Repository;

    public function __construct(private AdditionalFieldRule $model) {}
}
