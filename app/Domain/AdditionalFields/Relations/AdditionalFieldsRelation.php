<?php

namespace App\Domain\AdditionalFields\Relations;

use App\Domain\AdditionalFields\Models\AdditionalField;
use App\Domain\AdditionalFields\Models\AdditionalFieldRule;
use App\Domain\AdditionalFields\Models\AdditionalFieldValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AdditionalFieldsRelation extends Relation
{
    public function __construct(
        Builder $query,
        Model $parent,
        private readonly string $path,
        private readonly string $key = 'uuid',
    ) {
        parent::__construct($query, $parent);
    }

    public function addConstraints(): void
    {
        if (! static::$constraints) {
            return;
        }

        $modelTarget = $this->modelTarget();

        if ($modelTarget === null) {
            $this->query->whereRaw('0 = 1');

            return;
        }

        $this->query
            ->where('target', $modelTarget)
            ->whereIn(
                'additional_field_uuid',
                $this->rulesQuery()->select('additional_field_uuid'),
            );
    }

    public function addEagerConstraints(array $models): void
    {
        $targets = $this->modelTargets($models);

        if ($targets === []) {
            $this->query->whereRaw('0 = 1');

            return;
        }

        $this->query
            ->whereIn('target', $targets)
            ->with('additionalField');
    }

    public function initRelation(array $models, $relation): array
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    public function match(array $models, EloquentCollection $results, $relation): array
    {
        foreach ($models as $model) {
            $modelTarget = $this->modelTargetFor($model);

            if ($modelTarget === null) {
                $model->setRelation($relation, $this->related->newCollection());

                continue;
            }

            $allowedUuids = $this->allowedFieldUuidsFor($model);

            $model->setRelation(
                $relation,
                $results->filter(
                    fn (AdditionalFieldValue $value) => $value->target === $modelTarget
                        && $allowedUuids->contains($value->additional_field_uuid),
                )->values(),
            );
        }

        return $models;
    }

    public function getResults(): EloquentCollection
    {
        return $this->records();
    }

    public function allowedFields(): Collection
    {
        $ownerTarget = $this->ownerTarget();

        if ($ownerTarget === null) {
            return collect();
        }

        return AdditionalField::query()
            ->whereIn(
                'uuid',
                $this->rulesQuery()->select('additional_field_uuid'),
            )
            ->get();
    }

    public function exists(): bool
    {
        return $this->rulesQuery()->exists();
    }

    public function rules(): Collection
    {
        $ownerTarget = $this->ownerTarget();

        if ($ownerTarget === null) {
            return collect();
        }

        return $this->rulesQuery()
            ->with('additionalField')
            ->get();
    }

    public function records(): EloquentCollection
    {
        $modelTarget = $this->modelTarget();

        if ($modelTarget === null) {
            return $this->related->newCollection();
        }

        return AdditionalFieldValue::query()
            ->where('target', $modelTarget)
            ->whereIn(
                'additional_field_uuid',
                $this->rulesQuery()->select('additional_field_uuid'),
            )
            ->with('additionalField')
            ->get();
    }

    public function sync(array $items): void
    {
        $modelTarget = $this->modelTarget();

        if ($modelTarget === null) {
            throw ValidationException::withMessages([
                'additional_field_values' => ['Unable to resolve additional fields owner.'],
            ]);
        }

        $allowedUuids = $this->allowedFields()->pluck('uuid');

        AdditionalFieldValue::query()
            ->where('target', $modelTarget)
            ->whereNotIn('additional_field_uuid', $allowedUuids)
            ->delete();

        foreach ($items as $index => $item) {
            $fieldUuid = $item['additional_field_uuid'] ?? null;

            if (! $allowedUuids->contains($fieldUuid)) {
                throw ValidationException::withMessages([
                    "additional_field_values.{$index}.additional_field_uuid" => [
                        __('This field does not belong to the selected category.'),
                    ],
                ]);
            }

            AdditionalFieldValue::query()->updateOrCreate(
                [
                    'target' => $modelTarget,
                    'additional_field_uuid' => $fieldUuid,
                ],
                [
                    'value' => $item['value'] ?? null,
                ],
            );
        }
    }

    private function owner(): ?Model
    {
        $owner = data_get($this->parent, $this->path);

        return $owner instanceof Model ? $owner : null;
    }

    private function ownerTarget(): ?string
    {
        $value = data_get($this->owner(), $this->key);

        return $value !== null ? (string) $value : null;
    }

    private function modelTarget(): ?string
    {
        return $this->modelTargetFor($this->parent);
    }

    private function modelTargetFor(Model $model): ?string
    {
        $value = data_get($model, $this->key);

        return $value !== null ? (string) $value : null;
    }

    private function modelTargets(array $models): array
    {
        return collect($models)
            ->map(fn (Model $model) => $this->modelTargetFor($model))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function allowedFieldUuidsFor(Model $model): Collection
    {
        $ownerTarget = $this->ownerTargetFor($model);

        if ($ownerTarget === null) {
            return collect();
        }

        return AdditionalFieldRule::query()
            ->where('target', $ownerTarget)
            ->pluck('additional_field_uuid');
    }

    private function ownerTargetFor(Model $model): ?string
    {
        $owner = data_get($model, $this->path);

        if (! $owner instanceof Model) {
            return null;
        }

        $value = data_get($owner, $this->key);

        return $value !== null ? (string) $value : null;
    }

    private function rulesQuery(): Builder
    {
        $ownerTarget = $this->ownerTarget();

        if ($ownerTarget === null) {
            return AdditionalFieldRule::query()->whereRaw('0 = 1');
        }

        return AdditionalFieldRule::query()->where('target', $ownerTarget);
    }
}
