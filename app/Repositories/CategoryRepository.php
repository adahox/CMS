<?php

namespace App\Repositories;

use App\Interfaces\RepositoriesInterface;
use App\Models\Category;
use App\Traits\Repository;

class CategoryRepository implements RepositoriesInterface
{
    use Repository;

    public function __construct(public Category $model) {}
}
