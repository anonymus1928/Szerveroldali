<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $submitter) {
            $tmpUsers = $users->where('id', '!=', $submitter->id)->random(5);
            Ticket::factory()
                ->hasAttached(
                    $submitter, ['is_submitter' => true, 'is_responsible' => true]
                )
                ->hasAttached(
                    $tmpUsers, ['is_submitter' => false, 'is_responsible' => false]
                )
                ->create();
        }
    }
}
