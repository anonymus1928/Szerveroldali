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
            // Comment::factory()->for($ticket)->create(['user_id' => $ticket->owner->first()->id]);
            $users = $ticket->notOwner->random(3);
            foreach ($users as $user) {
                Comment::factory()->for($ticket)->create(['user_id' => $user->id]);
            }
        }
    }
}
