<?php

namespace App\Domain\AdditionalFields\Concerns;

use Illuminate\Support\Str;

trait UuidGenerator
{
    protected static function bootUuidGenerator(): void
    {
        static::creating(function ($model) {
            if (! $model->uuid) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
