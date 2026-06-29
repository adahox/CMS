<?php

namespace App\Domain\AdditionalFields\Models;

use App\Domain\AdditionalFields\Concerns\UuidGenerator;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded(['id', 'uuid'])]
class AdditionalFieldRule extends Model
{
    use HasFactory, UuidGenerator;

    public function additionalField(): BelongsTo
    {
        return $this->belongsTo(AdditionalField::class, 'additional_field_uuid', 'uuid');
    }
}
