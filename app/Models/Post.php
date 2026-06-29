<?php

namespace App\Models;

use App\Domain\AdditionalFields\Attributes\AdditionalFieldsPath;
use App\Domain\AdditionalFields\Concerns\HasAdditionalFields;
use App\Domain\AdditionalFields\Relations\AdditionalFieldsRelation;
use App\Scopes\PostScopes;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[AdditionalFieldsPath('category')]
#[Guarded(['id', 'uuid'])]
class Post extends Model
{
    use HasAdditionalFields, HasFactory, PostScopes, UuidGenerator;

    protected $with = ['category'];

    public function scopeFilter($query, array $filters)
    {
        return $this->createFilter($query, $filters);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    public function extraFields(): AdditionalFieldsRelation
    {
        return $this->additionalFields();
    }
}
