<?php

namespace App\Domain\AdditionalFields\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AdditionalFieldsPath
{
    public function __construct(public readonly string $path, public readonly string $key = 'uuid') {}
}
