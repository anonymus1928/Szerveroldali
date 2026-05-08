<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Ticket;

final readonly class Statistics
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        return [
            'active_ticket_number' => Ticket::where('done', false)->count(),
            'comments_avg' => Ticket::withCount('comments')->get()->avg('comments_count'),
            'most_comments' => Ticket::withCount('comments')->orderByDesc('comments_count')->first(),
        ];
    }
}
