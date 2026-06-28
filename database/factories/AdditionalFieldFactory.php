<?php

namespace Database\Factories;

use App\Models\AdditionalField;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionalFieldFactory extends Factory
{
    protected $model = AdditionalField::class;

    public function definition(): array
    {
        return [
            'label' => fake()->words(2, true),
            'type' => 'text',
            'options' => null,
        ];
    }
}
