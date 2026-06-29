<?php

namespace App\Http\Requests\AdditionalFields;

use App\Interfaces\ValidateInputData;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdditionalFieldStoreRequest extends FormRequest implements ValidateInputData
{
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'target' => ['required', 'uuid', Rule::exists(Category::class, 'uuid')],
        ];
    }
}
