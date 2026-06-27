<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostService
{
    public function __construct(private PostRepository $repository) {}

    /**
     * @return Collection<int, Post>
     */
    public function list(): Collection
    {
        return $this->repository->all();
    }

    public function findByUuid(string $uuid): Post
    {
        $post = $this->repository->findByUuid($uuid);

        if (! $post) {
            throw new NotFoundHttpException(__('Post not found.'));
        }

        return $post;
    }

    public function create(array $data): Post
    {
        return $this->repository->create($data);
    }

    public function update(string $uuid, array $data): Post
    {
        $post = $this->findByUuid($uuid);

        return $this->repository->update($post, $data);
    }

    public function delete(string $uuid): void
    {
        $post = $this->findByUuid($uuid);

        $this->repository->delete($post);
    }
}
