<?php

namespace App\Models;

use App\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded(['id', 'uuid'])]
class Category extends Model
{
    use HasFactory, HasUuid;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_uuid', 'uuid');
    }
}
