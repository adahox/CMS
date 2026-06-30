<?php

namespace App\Strategies\Standard;

use App\Adapters\PostAdapter;
use App\Http\Requests\Posts\PostStoreRequest;
use App\Http\Requests\Posts\PostUpdateRequest;
use App\Interfaces\ServicesInterface;
use App\Repositories\PostRepository;
use App\Traits\InputValidations;
use Exception;
use Illuminate\Support\Facades\DB;

class Post implements ServicesInterface
{
    use InputValidations;

    public function __construct(private readonly PostRepository $repository) {}

    public function toList(array $filter = []): array
    {
        $data = $this->repository->filter($filter);

        return PostAdapter::adaptMany($data);
    }

    public function getByUuid(string $uuid): array
    {
        $post = $this->repository->find($uuid);

        if (! $post) {
            throw new Exception(__('Post not found.'));
        }

        return PostAdapter::adapt($post);
    }

    public function toCreate(array $data): array
    {
        $validated = $this->validate(new PostStoreRequest, $data);
        [$postData, $values] = $this->splitAdditionalFieldValues($validated);

        $post = DB::transaction(function () use ($postData, $values) {
            $post = $this->repository->create($postData);
            $post->additionalFields()->sync($values);

            return $post->refresh();
        });

        return PostAdapter::adapt($post);
    }

    public function updateByUuid(string $uuid, array $data): array
    {
        $validated = $this->validate(new PostUpdateRequest, array_merge($data, ['uuid' => $uuid]));
        [$postData, $values] = $this->splitAdditionalFieldValues($validated);

        $post = DB::transaction(function () use ($uuid, $postData, $values) {
            $post = $this->repository->find($uuid);

            if (! $post) {
                throw new Exception(__('Post not found.'));
            }

            $post = $this->repository->update($post, $postData);
            $post->additionalFields()->sync($values);

            return $post->refresh();
        });

        return PostAdapter::adapt($post);
    }

    public function deleteByUuid(string $uuid): void
    {
        $post = $this->repository->find($uuid);

        if (! $post) {
            throw new Exception(__('Post not found.'));
        }

        $this->repository->delete($post);
    }

    private function splitAdditionalFieldValues(array $data): array
    {
        $values = $data['additional_field_values'] ?? [];
        unset($data['additional_field_values'], $data['uuid']);

        return [$data, $values];
    }
}
