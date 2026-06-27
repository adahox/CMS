<?php

namespace App\Http\Requests\Posts;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_uuid' => ['required', 'uuid', Rule::exists(Category::class, 'uuid')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ];
    }
}
