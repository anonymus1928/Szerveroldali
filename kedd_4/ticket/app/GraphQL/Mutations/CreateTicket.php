<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

final readonly class CreateTicket
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $ticket = Ticket::create($args);
        $ticket->users()->attach(Auth::id(), ['owner' => true]);
        $ticket->comments()->create([
            'text' => $args['text'],
            'user_id' => Auth::id(),
        ]);
        return $ticket;
    }
}
