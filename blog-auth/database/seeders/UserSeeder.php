<?php

namespace Database\Seeders;

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
            'is_admin' => true,
        ]);

        // User::factory()->create([
        //     'name' => 'Super Admin',
        //     'email' => 'q@q.hu',
        //     'password' => Hash::make('q'),
        // ]);

        User::factory()->count(10)->hasPosts(10)->create();

        // User::factory(10)->has(Post::factory(10), 'posts')->create();
    }
}
