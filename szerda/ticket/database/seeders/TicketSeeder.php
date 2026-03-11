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
        $users = User::all();
        foreach ($users as $user) {
            $tmpUsers = $users->where('id', '<>', $user->id)->random(fake()->numberBetween(2,5));

            for ($i=0; $i < fake()->numberBetween(1, 5); $i++) {
                Ticket::factory()->hasAttached($user, ['is_owner' => true])
                                 ->hasAttached($tmpUsers, ['is_owner' => false])
                                 ->hasComments(1, ['user_id' => $user->id])
                                 ->create();
            }
        }
    }
}
