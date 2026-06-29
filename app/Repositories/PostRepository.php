<?php

namespace App\Repositories;

use App\Interfaces\RepositoriesInterface;
use App\Models\Post;
use App\Traits\Repository;

class PostRepository implements RepositoriesInterface
{
    use Repository;

    public function __construct(public Post $model) {}
}
