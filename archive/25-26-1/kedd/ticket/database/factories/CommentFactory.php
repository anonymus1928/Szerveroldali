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
        $filename = fake()->boolean() ? 'file.png' : null;
        return [
            'description' => fake()->paragraph(fake()->numberBetween(1, 6)),
            'filename' => $filename,
            'filename_hash' => $filename,
        ];
    }
}
