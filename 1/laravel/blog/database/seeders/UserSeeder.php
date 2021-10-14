<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'q@q.hu',
            'password' => Hash::make('q'),
        ]);

        // Magic method
        User::factory()->count(10)->hasPosts(5)->create();

        // Has many relációnál 'has' metódus -> has(FACTORY, relációt_definiáló_metódus)
        // Belongs to relációnál ugyanez, csak a 'has' helyett 'for'.
        // User::factory(10)->has(Post::factory(5), 'posts')->create();
    }
}
