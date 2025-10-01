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
            'admin' => true,
            'password' => Hash::make('q'),
        ]);

        User::factory()->create([
            'name' => 'Super User',
            'email' => 'qq@q.hu',
            'admin' => false,
            'password' => Hash::make('q'),
        ]);

        User::factory(10)->create();

        $this->call([TicketSeeder::class, CommentSeeder::class]);
    }
}
