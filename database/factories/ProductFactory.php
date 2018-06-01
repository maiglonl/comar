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
	$value = rand(1,100)*100;
	return [
		'name' => $faker->name(),
		'description' => $faker->paragraph(),
		'category_id' => rand(1,5),
		'value_partner' => $value*0.7,
		'value_seller' => $value,
		'weight' => rand(1,100),
		'height' => rand(1,100),
		'width' => rand(1,100),
		'length' => rand(1,100),
		'diameter' => rand(1,100),
		'status' => 1
	];
});