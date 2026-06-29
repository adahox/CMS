<?php

namespace App\Domain\AdditionalFields\Concerns;

use App\Domain\AdditionalFields\Attributes\AdditionalFieldsPath;
use App\Domain\AdditionalFields\Models\AdditionalFieldValue;
use App\Domain\AdditionalFields\Relations\AdditionalFieldsRelation;
use LogicException;
use ReflectionClass;

trait HasAdditionalFields
{
    public function additionalFields(): AdditionalFieldsRelation
    {
        $config = $this->resolveAdditionalFieldsPath();

        return new AdditionalFieldsRelation(
            AdditionalFieldValue::query(),
            $this,
            $config->path,
            $config->key,
        );
    }

    protected function resolveAdditionalFieldsPath(): AdditionalFieldsPath
    {
        $attributes = (new ReflectionClass(static::class))->getAttributes(AdditionalFieldsPath::class);

        if ($attributes === []) {
            throw new LogicException(sprintf(
                '%s must define #[AdditionalFieldsPath] to use HasAdditionalFields.',
                static::class,
            ));
        }

        return $attributes[0]->newInstance();
    }
}
