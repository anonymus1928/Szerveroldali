<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

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
            'disable_comments' => $this->faker->boolean(),
            'hide_post' => $this->faker->boolean(),
        ];
    }
}
