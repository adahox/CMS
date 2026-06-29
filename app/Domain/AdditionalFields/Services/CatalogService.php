<?php

namespace App\Domain\AdditionalFields\Services;

use App\Domain\AdditionalFields\Models\AdditionalField;
use App\Domain\AdditionalFields\Models\AdditionalFieldRule;
use App\Domain\AdditionalFields\Repositories\AdditionalFieldRepository;
use App\Domain\AdditionalFields\Repositories\AdditionalFieldRuleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogService
{
    public function list(array $filter = []): Collection
    {
        return app(AdditionalFieldRepository::class)->filter($filter);
    }

    public function find(string $uuid): AdditionalField
    {
        $field = app(AdditionalFieldRepository::class)->find($uuid);

        if (! $field) {
            throw new NotFoundHttpException(__('Additional field not found.'));
        }

        return $field->load('rule');
    }

    public function create(array $data): AdditionalField
    {
        $target = $data['target'];
        unset($data['target']);

        return DB::transaction(function () use ($data, $target) {
            $field = app(AdditionalFieldRepository::class)->create($data);

            app(AdditionalFieldRuleRepository::class)->create([
                'target' => $target,
                'additional_field_uuid' => $field->uuid,
            ]);

            return $field->refresh()->load('rule');
        });
    }

    public function update(string $uuid, array $data): AdditionalField
    {
        $field = $this->find($uuid);
        $target = $data['target'] ?? null;
        unset($data['target']);

        return DB::transaction(function () use ($field, $data, $target) {
            app(AdditionalFieldRepository::class)->update($field, $data);

            if ($target !== null) {
                AdditionalFieldRule::query()->updateOrCreate(
                    ['additional_field_uuid' => $field->uuid],
                    ['target' => $target],
                );
            }

            return $field->refresh()->load('rule');
        });
    }

    public function delete(string $uuid): void
    {
        $field = $this->find($uuid);

        app(AdditionalFieldRepository::class)->delete($field);
    }
}
