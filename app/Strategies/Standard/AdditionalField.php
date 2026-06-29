<?php

namespace App\Strategies\Standard;

use App\Adapters\AdditionalFieldAdapter;
use App\Domain\AdditionalFields\Models\AdditionalFieldRule;
use App\Domain\AdditionalFields\Repositories\AdditionalFieldRepository;
use App\Domain\AdditionalFields\Repositories\AdditionalFieldRuleRepository;
use App\Http\Requests\AdditionalFields\AdditionalFieldStoreRequest;
use App\Http\Requests\AdditionalFields\AdditionalFieldUpdateRequest;
use App\Interfaces\ServicesInterface;
use App\Traits\InputValidations;
use Exception;
use Illuminate\Support\Facades\DB;

class AdditionalField implements ServicesInterface
{
    use InputValidations;

    public function __construct(
        private readonly AdditionalFieldRepository $repository,
        private readonly AdditionalFieldRuleRepository $ruleRepository,
    ) {}

    public function toList(array $filter = []): array
    {
        $data = $this->repository->filter($filter);

        return AdditionalFieldAdapter::adaptMany($data);
    }

    public function getByUuid(string $uuid): array
    {
        $field = $this->repository->find($uuid);

        if (! $field) {
            throw new Exception(__('Additional field not found.'));
        }

        return AdditionalFieldAdapter::adapt($field->load('rule'));
    }

    public function toCreate(array $data): array
    {
        $validated = $this->validate(new AdditionalFieldStoreRequest, $data);
        $target = $validated['target'];
        unset($validated['target']);

        $field = DB::transaction(function () use ($validated, $target) {
            $field = $this->repository->create($validated);

            $this->ruleRepository->create([
                'target' => $target,
                'additional_field_uuid' => $field->uuid,
            ]);

            return $field->refresh()->load('rule');
        });

        return AdditionalFieldAdapter::adapt($field);
    }

    public function updateByUuid(string $uuid, array $data): array
    {
        $validated = $this->validate(new AdditionalFieldUpdateRequest, array_merge($data, ['uuid' => $uuid]));
        $target = $validated['target'];
        unset($validated['target'], $validated['uuid']);

        $field = $this->repository->find($uuid);

        if (! $field) {
            throw new Exception(__('Additional field not found.'));
        }

        $field = DB::transaction(function () use ($field, $validated, $target) {
            $field = $this->repository->update($field, $validated);

            AdditionalFieldRule::query()->updateOrCreate(
                ['additional_field_uuid' => $field->uuid],
                ['target' => $target],
            );

            return $field->refresh()->load('rule');
        });

        return AdditionalFieldAdapter::adapt($field);
    }

    public function deleteByUuid(string $uuid): void
    {
        $field = $this->repository->find($uuid);

        if (! $field) {
            throw new Exception(__('Additional field not found.'));
        }

        $this->repository->delete($field);
    }
}
