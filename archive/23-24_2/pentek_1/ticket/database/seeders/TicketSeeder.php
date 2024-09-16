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
        foreach($users as $user) {
            $tmpUsers = $users->where('id', '!=', $user->id)->random(5);
            Ticket::factory()
                        ->hasAttached($user, ['owner' => true])
                        ->hasAttached($tmpUsers, ['owner' => false])
                        ->create();
        }
    }
}
