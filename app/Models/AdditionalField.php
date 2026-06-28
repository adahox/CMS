<?php

namespace App\Models;

use App\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Guarded(['id', 'uuid'])]
class AdditionalField extends Model
{
    use HasFactory, HasUuid;

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
