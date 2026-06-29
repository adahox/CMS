<?php

namespace App\Http\Requests\Categories;

use App\Interfaces\ValidateInputData;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest implements ValidateInputData
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
