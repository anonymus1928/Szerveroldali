<?php

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create();

echo $faker->name;

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

use Carbon\Carbon;

printf("Now: %s", Carbon::now());

