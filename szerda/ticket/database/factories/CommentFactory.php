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
        $image = null;
        if(fake()->boolean()) {
            $image = 'almafa.png';
        }
        return [
            'description' => fake()->paragraph(),
            'filename' => $image,
            'filename_hash' => $image,
        ];
    }
}
