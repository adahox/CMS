<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository
{
    public function __construct(private Post $model) {}

    /**
     * @return Collection<int, Post>
     */
    public function all(): Collection
    {
        return $this->model->newQuery()->latest()->get();
    }

    public function findByUuid(string $uuid): ?Post
    {
        return $this->model->newQuery()->where('uuid', $uuid)->first();
    }

    public function create(array $data): Post
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        return $post->refresh();
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
