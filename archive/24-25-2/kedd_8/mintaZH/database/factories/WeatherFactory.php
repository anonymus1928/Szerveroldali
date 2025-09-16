<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Weather>
 */
class WeatherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake() -> randomElement(['sunny', 'rain', 'cloudy', 'thunder', 'other']),
            'temp' => fake() -> randomFloat(1, -20, 40),
            'logged_at' => fake() -> dateTimeBetween(),
        ];
    }
}
