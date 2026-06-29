<?php

namespace App\Domain\AdditionalFields;

use App\Domain\AdditionalFields\Attributes\AdditionalFieldsPath;
use App\Domain\AdditionalFields\Models\AdditionalField;
use App\Domain\AdditionalFields\Models\AdditionalFieldRule;
use App\Domain\AdditionalFields\Models\AdditionalFieldValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

class AdditionalFields
{
    public function __construct(
        private Model $model,
        private string $path,
        private string $key = 'uuid',
    ) {}

    public static function for(Model $model): ?self
    {
        $attributes = (new ReflectionClass($model))->getAttributes(AdditionalFieldsPath::class);

        if ($attributes === []) {
            return null;
        }

        $config = $attributes[0]->newInstance();

        return new self($model, $config->path, $config->key);
    }

    public function get(): Collection
    {
        $ownerTarget = $this->ownerTarget();

        if (! $ownerTarget) {
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

    public function first(): ?AdditionalField
    {
        return $this->get()->first();
    }

    public function rules(): Collection
    {
        $ownerTarget = $this->ownerTarget();

        if (! $ownerTarget) {
            return collect();
        }

        return $this->rulesQuery()
            ->with('additionalField')
            ->get();
    }

    public function values(): Collection
    {
        return $this->valuesRelation()
            ->whereIn(
                'additional_field_uuid',
                $this->rulesQuery()->select('additional_field_uuid'),
            )
            ->with('additionalField')
            ->get();
    }

    public function records(): Collection
    {
        return $this->values();
    }

    public function sync(array $items): void
    {
        $modelTarget = $this->modelTarget();

        if (! $modelTarget) {
            throw ValidationException::withMessages([
                'additional_field_values' => ['Unable to resolve additional fields owner.'],
            ]);
        }

        $allowedUuids = $this->get()->pluck('uuid');

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

    private function valuesRelation(): HasMany
    {
        return $this->model->hasMany(
            AdditionalFieldValue::class,
            'target',
            $this->key,
        );
    }

    private function owner(): ?Model
    {
        $owner = data_get($this->model, $this->path);

        return $owner instanceof Model ? $owner : null;
    }

    private function ownerTarget(): ?string
    {
        $value = data_get($this->owner(), $this->key);

        return $value !== null ? (string) $value : null;
    }

    private function modelTarget(): ?string
    {
        $value = data_get($this->model, $this->key);

        return $value !== null ? (string) $value : null;
    }

    private function rulesQuery(): Builder
    {
        $ownerTarget = $this->ownerTarget();

        if (! $ownerTarget) {
            return AdditionalFieldRule::query()->whereRaw('0 = 1');
        }

        return AdditionalFieldRule::query()->where('target', $ownerTarget);
    }
}
