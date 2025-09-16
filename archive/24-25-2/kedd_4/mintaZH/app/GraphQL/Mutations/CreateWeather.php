<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Weather;

final readonly class CreateWeather
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        try {
            $weather = Weather::create($args);
            return $weather;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
