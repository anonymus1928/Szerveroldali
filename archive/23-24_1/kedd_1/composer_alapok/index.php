<?php
require_once 'vendor/autoload.php';

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();
// generate data by calling methods
echo $faker->name();
echo "\n";
// 'Vince Sporer'
echo $faker->email();
echo "\n";
// 'walter.sophia@hotmail.com'
echo $faker->text();
echo "\n";
// 'Numquam ut mollitia at consequuntur inventore dolorem.'