<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Location;

final readonly class SetPublic
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $id = $args['location_id'];
        $public = $args['public'];

        $location = Location::find($id);
        if(!$location) {
            return 'NOT FOUND';
        }
        if($location->public == $public) {
            return 'ALREADY SET';
        }
        $location->update(['public' => $public]);
        return 'CHANGED';
    }
}
