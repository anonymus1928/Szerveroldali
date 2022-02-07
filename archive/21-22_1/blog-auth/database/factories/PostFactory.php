<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(6, true),
            'text' => $this->faker->text(300),
            'disabled_comments' => $this->faker->boolean(),
            'hide_post' => $this->faker->boolean(),
        ];
    }
}
