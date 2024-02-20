<?php
require_once 'vendor/autoload.php';

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();
echo "\n";
// generate data by calling methods
echo $faker->name();
echo "\n";
// 'Vince Sporer'
echo $faker->email();
// 'walter.sophia@hotmail.com'
echo "\n";
echo $faker->text();
// 'Numquam ut mollitia at consequuntur inventore dolorem.'