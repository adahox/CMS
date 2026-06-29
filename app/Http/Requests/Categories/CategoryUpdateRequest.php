<?php

namespace App\Http\Requests\Categories;

use App\Interfaces\ValidateInputData;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest implements ValidateInputData
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'uuid' => $this->route('uuid'),
        ]);
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid', Rule::exists(Category::class, 'uuid')],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
