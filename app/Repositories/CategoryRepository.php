<?php

namespace App\Repositories;

use App\Concerns\Repository;
use App\Models\Category;

class CategoryRepository
{
    use Repository;

    public function __construct(private Category $model) {}
}
