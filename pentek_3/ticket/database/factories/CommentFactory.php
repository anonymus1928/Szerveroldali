<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tmp = fake()->boolean() ? 'image.png' : null;
        return [
            'text' => fake()->paragraph(),
            'filename' => $tmp,
            'filename_hash' => $tmp,
        ];
    }
}
