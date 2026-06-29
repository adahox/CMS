<?php

namespace App\Services;

use App\Domain\AdditionalFields\AdditionalFields;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
        [$postData, $values] = $this->splitAdditionalFieldValues($data);

        return DB::transaction(function () use ($postData, $values) {
            $post = app(PostRepository::class)->create($postData);
            AdditionalFields::for($post)?->sync($values);

            return $post->refresh()->load('category');
        });
    }

    public function update(string $uuid, array $data): Post
    {
        [$postData, $values] = $this->splitAdditionalFieldValues($data);

        return DB::transaction(function () use ($uuid, $postData, $values) {
            $post = $this->find($uuid);
            app(PostRepository::class)->update($post, $postData);
            AdditionalFields::for($post->refresh()->load('category'))?->sync($values);

            return $post->refresh()->load('category');
        });
    }

    public function delete(string $uuid): void
    {
        $post = app(PostRepository::class)->find($uuid);

        if (! $post) {
            throw new NotFoundHttpException(__('Post not found.'));
        }

        app(PostRepository::class)->delete($post);
    }

    public function present(Post $post): array
    {
        $payload = $post->toArray();

        if ($fields = AdditionalFields::for($post)) {
            $payload['additional_field_values'] = $fields->records()->values()->all();
        }

        return $payload;
    }

    public function presentMany(Collection $posts): array
    {
        return $posts->map(fn (Post $post) => $this->present($post))->all();
    }

    private function splitAdditionalFieldValues(array $data): array
    {
        $values = $data['additional_field_values'] ?? [];
        unset($data['additional_field_values'], $data['uuid']);

        return [$data, $values];
    }
}
