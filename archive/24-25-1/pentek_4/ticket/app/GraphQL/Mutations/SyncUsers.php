<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Ticket;
use Error;
use Illuminate\Support\Facades\Auth;

final readonly class SyncUsers
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticketId']);

        if(!Auth::user()->admin && !$ticket->users->contains(Auth::id())) {
            throw new Error('Unauthorized.');
        }

        $users = $ticket->users;

        $output = [
            'successUp' => [],
            'successDown' => [],
            'errorUp' => [],
            'errorDown' => [],
        ];

        foreach($args['up'] as $userUp) {
            if($users->contains($userUp)) {
                $output['errorUp'][] = $userUp;
            } else {
                $ticket->users()->attach($userUp);
                $output['successUp'][] = $userUp;
            }
        }

        foreach($args['down'] as $userDown) {
            if($users->contains($userDown)) {
                $ticket->users()->detach($userDown);
                $output['successDown'][] = $userDown;
            } else {
                $output['errorDown'][] = $userDown;
            }
        }

        return $output;
    }
}
