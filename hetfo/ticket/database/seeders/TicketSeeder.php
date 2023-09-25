<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\Ticket::factory(10)->create();
        $users = User::all();

        foreach($users as $submitter) {
            $tmpUsers = $users->where('id', '!=', $submitter->id)->random(5);
            Ticket::factory()
                        ->hasAttached(
                            $submitter, ['is_author' => true, 'is_responsible' => true]
                        )
                        ->hasAttached(
                            $tmpUsers, ['is_author' => false, 'is_responsible' => fake()->boolean()]
                        )
                        ->create();
        }
    }
}
