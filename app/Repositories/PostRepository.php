<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository
{
    public function __construct(private Post $model) {}

    public function all(): Collection
    {
        return $this->model->newQuery()->with('category')->latest()->get();
    }

    public function findByUuid(string $uuid): ?Post
    {
        return $this->model->newQuery()->with('category')->where('uuid', $uuid)->first();
    }

    public function create(array $data): Post
    {
        $post = $this->model->newQuery()->create($data);

        return $post->load('category');
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        return $post->refresh()->load('category');
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
