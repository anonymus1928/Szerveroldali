<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Location;
use App\Models\Weather;

final readonly class Statistics
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {


        return [
            'locationCount' => Location::count(),
            'averageTemp' => Weather::all()->avg('temp'),
            'over30Celsius' => Weather::where('temp', '>', 30)->count(),
        ];
    }
}
