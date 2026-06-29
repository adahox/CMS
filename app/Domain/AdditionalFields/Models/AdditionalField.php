<?php

namespace App\Domain\AdditionalFields\Models;

use App\Domain\AdditionalFields\Concerns\UuidGenerator;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Guarded(['id', 'uuid'])]
class AdditionalField extends Model
{
    use HasFactory, UuidGenerator;

    protected $with = ['rule'];

    protected $hidden = ['rule'];

    protected $appends = ['target'];

    public function rule(): HasOne
    {
        return $this->hasOne(AdditionalFieldRule::class, 'additional_field_uuid', 'uuid');
    }

    public function getTargetAttribute(): ?string
    {
        return $this->rule?->target;
    }

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }
}
