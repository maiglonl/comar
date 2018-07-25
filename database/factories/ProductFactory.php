<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Product::class, function (Faker $faker) {
	$value = rand(1,100)*10;
	$weight = $faker->randomFloat(2, 0.1, 5) ;
	return [
		'name' => $faker->name(),
		'description' => $faker->paragraph(),
		'category_id' => rand(1,5),
		'value_partner' => $value,
		'value_seller' => $value*0.7,
		'weight' => $weight,
		'height' => rand(2,65),
		'width' => rand(11,65),
		'length' => rand(16,65),
		'diameter' => rand(1,100),
		'amount' => rand(1,100),
		'free_shipping' => rand(1,100) < 50 ? 0 : 1,
		'interest_free' => rand(1,100) < 50 ? 6 : 12,
		'status' => 1
	];
});