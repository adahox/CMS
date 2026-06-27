<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'category_uuid' => fn () => Category::factory()->create()->uuid,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}
