<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostService
{
    public function list(array $filter = []): Collection
    {
        return app(PostRepository::class)->filter($filter);
    }

    public function find(string $uuid): Post
    {
        $post = app(PostRepository::class)->find($uuid);

        if (! $post) {
            throw new NotFoundHttpException(__('Post not found.'));
        }

        return $post;
    }

    public function create(array $data): Post
    {
        return app(PostRepository::class)->create($data);
    }

    public function update(string $uuid, array $data): Post
    {
        $post = $this->find($uuid);

        return app(PostRepository::class)->update($post, $data);
    }

    public function delete(string $uuid): void
    {
        $post = $this->find($uuid);

        app(PostRepository::class)->delete($post);
    }
}
