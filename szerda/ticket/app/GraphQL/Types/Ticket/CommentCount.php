<?php declare(strict_types=1);

namespace App\GraphQL\Types\Ticket;

use App\Models\Ticket;

final readonly class CommentCount
{
    /** @param  array{}  $args */
    public function __invoke(Ticket $ticket, array $args)
    {
        return $ticket->comments->count();
    }
}
