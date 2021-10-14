<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
        ]);


        $categories = Category::all();
        $categories_count = $categories->count();

        $users = User::all();
        $users_count = $users->count();

        Post::all()->each(function ($post) use (&$categories, &$categories_count, &$users, &$users_count) {
            $category_ids = $categories->random(rand(1, $categories_count))->pluck('id')->toArray();

            $post->categories()->attach($category_ids);

            // Ha nem a UserSeeder-ben kötjük össze a post-ot a user-rel
            // if($users_count > 0) {
            //     $post->author()->associate($users->random());
            //     $post->save();
            // }
        });
    }
}
