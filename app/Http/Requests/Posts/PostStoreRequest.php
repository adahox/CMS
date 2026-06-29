<?php

namespace App\Http\Requests\Posts;

use App\Domain\AdditionalFields\Models\AdditionalField;
use App\Interfaces\ValidateInputData;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest implements ValidateInputData
{
    public function rules(): array
    {
        return [
            'category_uuid' => ['required', 'uuid', Rule::exists(Category::class, 'uuid')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'additional_field_values' => ['nullable', 'array'],
            'additional_field_values.*.additional_field_uuid' => [
                'required',
                'uuid',
                Rule::exists(AdditionalField::class, 'uuid'),
            ],
            'additional_field_values.*.value' => ['nullable', 'string'],
        ];
    }
}
