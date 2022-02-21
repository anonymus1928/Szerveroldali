<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Comment::factory(10)->create();
        $tickets = Ticket::all();
        foreach ($tickets as $ticket) {
            Comment::factory()
                ->for($ticket)
                ->create(['user_id' => $ticket->submitter->first()->id]);
            $users = $ticket->notSubmitter->random(3);
            foreach ($users as $user) {
                Comment::factory()
                    ->for($ticket)
                    ->create(['user_id' => $user->id]);
            }
        }
    }
}
