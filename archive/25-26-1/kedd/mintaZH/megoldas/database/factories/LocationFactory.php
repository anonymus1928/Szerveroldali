<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Password;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake() -> unique() -> words(3, true),
            'email' => fake() -> unique() -> safeEmail(),
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'lat' => fake() -> randomFloat(5, -90, 90),
            'lon' => fake() -> randomFloat(5, -180, 180),
            'public' => fake() -> boolean(80),
        ];
    }
}
