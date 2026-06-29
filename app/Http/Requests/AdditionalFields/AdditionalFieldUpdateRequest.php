<?php

namespace App\Http\Requests\AdditionalFields;

use App\Domain\AdditionalFields\Models\AdditionalField;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdditionalFieldUpdateRequest extends FormRequest
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
            'uuid' => ['required', 'uuid', Rule::exists(AdditionalField::class, 'uuid')],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'target' => ['required', 'uuid', Rule::exists(Category::class, 'uuid')],
        ];
    }
}
