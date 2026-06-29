<?php

namespace App\Interfaces;

interface ServicesInterface
{
    public function toList(array $filter = []): array;
}
