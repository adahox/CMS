<?php

namespace App\Repositories;

use App\Concerns\Repository;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostRepository
{
    use Repository {
        create as traitCreate;
        find as traitFind;
        update as traitUpdate;
    }

    public function __construct(private Post $model) {}

    public function create(array $data): Post
    {
        $post = $this->traitCreate($data);

        return $post->load('category');
    }

    public function find(string $uuid): ?Post
    {
        $post = $this->traitFind($uuid);

        return $post?->load('category');
    }

    protected function newQuery(array $criteria = []): Builder
    {
        $query = $this->model->newQuery()->with('category');

        foreach ($criteria as $column => $value) {
            $query->where($column, $value);
        }

        return $query;
    }

    public function update(Model $model, array $data): Post
    {
        $post = $this->traitUpdate($model, $data);

        return $post->load('category');
    }
}
