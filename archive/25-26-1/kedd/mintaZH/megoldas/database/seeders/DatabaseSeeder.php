<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Warning;
use App\Models\Weather;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $warnings = Warning::factory(5) -> create();
        Location::factory(rand(10, 15)) -> create() -> each(function($loc) use ($warnings, $faker) {
            Weather::factory(rand(10, 15)) -> create([
                'location_id' => $loc
            ]) -> each(function ($we) use ($warnings, $faker) {
                $we -> warnings() -> sync($faker -> randomElements($warnings, rand(0, 5)));
            });
        });
    }
}
