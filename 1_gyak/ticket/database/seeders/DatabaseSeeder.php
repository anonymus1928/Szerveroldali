<?php

namespace Database\Seeders;

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
        User::factory()->hasTickets(5)->create(['is_admin' => true]);
        // $this->call([
        //     UserSeeder::class,
        //     TicketSeeder::class,
        //     CommentSeeder::class,
        // ]);
    }
}
