<?php

namespace App\Traits;

use App\Interfaces\ValidateInputData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait InputValidations
{
    protected function validate(ValidateInputData $request, array $data): array
    {
        $validator = Validator::make($data, $request->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
