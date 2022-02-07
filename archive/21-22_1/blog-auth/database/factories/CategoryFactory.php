<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        return [
            'name' => $this->faker->word(),
            'color' => Arr::random($colors),
        ];
    }
}
