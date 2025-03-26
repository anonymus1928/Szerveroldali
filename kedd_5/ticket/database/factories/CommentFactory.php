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
        $filename = fake()->boolean();
        return [
            'text' => fake()->paragraph(),
            // 'filename' => $filename ? 'image.png' : null,
            // 'filename_hash' => $filename ? 'image.png' : null,
        ];
    }
}
