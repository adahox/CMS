<?php

namespace App\Models;

use App\Scopes\CategoryScopes;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded(['id', 'uuid'])]
class Category extends Model
{
    use CategoryScopes, HasFactory, UuidGenerator;

    public function scopeFilter($query, array $filters)
    {
        return $this->createFilter($query, $filters);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_uuid', 'uuid');
    }
}
