<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::all();
        foreach($tickets as $ticket) {
            $users = $ticket->users;
            for ($i=0; $i < fake()->numberBetween(3, 10); $i++) {
                Comment::factory()->for($ticket)->for($users->random())->create();
            }
        }
    }
}
