<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'q@q.hu',
            'password' => Hash::make('q'),
            'admin' => true,
        ]);

        User::factory(10)->create();

        $this->call([
            TicketSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
