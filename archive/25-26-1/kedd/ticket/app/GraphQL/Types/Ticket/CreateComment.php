<?php declare(strict_types=1);

namespace App\GraphQL\Types\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

final readonly class CreateComment
{
    /** @param  array{}  $args */
    public function __invoke(Ticket $ticket, array $args)
    {
        $ticket->comments()->create([...$args, 'user_id' => Auth::id()]);

        return $ticket;
    }
}
