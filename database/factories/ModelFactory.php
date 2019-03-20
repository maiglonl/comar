<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Product::class, function () {
	$faker = Faker\Factory::create('pt_BR');
	$value = $faker->numberBetween(100, 500);
	return [
		'name' => $faker->name,
		'description' => $faker->paragraph,
		'category_id' => $faker->numberBetween(1, 5),
		'value_partner' => $value,
		'value_seller' => $value*0.7,
		'discount' => $faker->randomElements([0,5,10,15], 1)[0],
		'weight' => $faker->randomFloat(2, 0.1, 5),
		'height' => $faker->numberBetween(2,65),
		'width' => $faker->numberBetween(11,65),
		'length' => $faker->numberBetween(16,65),
		'diameter' => $faker->numberBetween(1,100),
		'quantity' => $faker->numberBetween(1,100),
		'free_shipping' => $faker->numberBetween(1,100) < 50 ? 0 : 1,
		'interest_free' => $faker->numberBetween(1,100) < 50 ? 6 : 12,
		'status' => 1
	];
});

$factory->define(App\Models\Order::class, function () {
	$faker = Faker\Factory::create('pt_BR');
	return [
		'status_id' => 3,
		'created_at' => $faker->dateTimeBetween('-6 months','+15 days')->format('Y-m-d H:i:s')
	];
});

$factory->define(App\Models\Item::class, function () {
	$faker = Faker\Factory::create('pt_BR');
	return [
		'quantity' => '1'
	];
});

$factory->define(App\Models\User::class, function () {
	$faker = Faker\Factory::create('pt_BR');
	$gender = $faker->numberBetween(1,100) < 50 ? 'male' : 'female';
	$name = trim(preg_replace(['/Sra\./', '/Srta\./', '/Dra\./', '/Sr\./', '/Dr\./', '/Filho/', '/Filha/', '/Neto/', '/Neta/', '/Sobrinho/', '/Sobrinha/', '/Jr\./'], '', $faker->name($gender)));
	$username = explode(' ', App\Helpers\StringHelper::removeAcentos($name));
	$data = [
		'name' => $name,
		'username' => strtolower($username[0]).'.'.strtolower($username[count($username)-1]),
		'password' => bcrypt('948571'),
		'email' => $faker->unique()->safeEmail,
		'phone1' => preg_replace('/\W/', '', $faker->phoneNumber),
		'phone2' => preg_replace('/\W/', '', $faker->phoneNumber),
		'cp' => $faker->unique()->cpf,
		'gender' => $gender,
		'birthdate' => $faker->dateTimeBetween('-50 years','-18 years')->format('Y-m-d'),
		'zipcode' => preg_replace('/\W/', '', $faker->postcode),
		'state' => $faker->stateAbbr,
		'city' => $faker->city,
		'district' => $faker->citySuffix,
		'street' => $faker->streetName,
		'number' => $faker->buildingNumber,
		'complement' => $faker->address,
		'status' => 1,
		'role' => $faker->numberBetween(1,100) < 50 ? 'seller' : 'partner',
		'remember_token' => str_random(10),
	];
	return $data;
});