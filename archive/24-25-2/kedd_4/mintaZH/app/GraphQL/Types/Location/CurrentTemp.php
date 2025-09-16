<?php declare(strict_types=1);

namespace App\GraphQL\Types\Location;

use App\Models\Location;

final readonly class CurrentTemp
{
    /** @param  array{}  $args */
    public function __invoke(Location $location, array $args)
    {
        if($location->weather->count() === 0) {
            return null;
        }
        return $location->weather->sortByDesc('logged_at')->first()->temp;
        // return $location->weather()->latest('logged_at')->first()->temp;
    }
}
