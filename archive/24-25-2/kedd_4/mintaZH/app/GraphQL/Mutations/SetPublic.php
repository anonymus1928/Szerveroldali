<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Location;

final readonly class SetPublic
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $location = Location::find($args['location_id']);
        if(!$location) {
            return "NOT FOUND";
        }

        if($location->public === $args['public']) {
            return "ALREADY SET";
        }

        $location->public = $args['public'];
        $location->save();
        return "CHANGED";
    }
}
