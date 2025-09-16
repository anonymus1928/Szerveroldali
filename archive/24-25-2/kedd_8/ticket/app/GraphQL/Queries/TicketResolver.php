<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Ticket;

final readonly class TicketResolver
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        return Ticket::find($args['id']);
    }
}
