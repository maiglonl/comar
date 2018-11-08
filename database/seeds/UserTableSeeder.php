<?php

use Illuminate\Database\Seeder;
use App\Repositories\UserRepository;

class UserTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		$faker = Faker\Factory::create('pt_BR');
		$repository = app(UserRepository::class);
		$repository->create([
			'name' => 'Maiglon Alexandre',
			'username' => 'maiglon',
			'password' => '948571',
			'email' => 'maiglonl@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => $faker->cpf,
			'gender' => 'male',
			'birthdate' => '1993-07-08',
			'zipcode' => '95890000',
			'state' => 'RS',
			'city' => 'Teutônia',
			'district' => 'Centro',
			'street' => 'Rua 20',
			'number' => '735',
			'complement' => 'Casa',
			'status' => 1,
			'role' => 'admin'
		]);
		$repository->create([
			'name' => 'Airton Wietholter',
			'username' => 'airton.wietholter',
			'password' => '12345',
			'email' => 'airtonw@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => $faker->cpf,
			'gender' => 'male',
			'birthdate' => '1990-01-01',
			'zipcode' => '95890000',
			'state' => 'RS',
			'city' => 'Teutônia',
			'district' => 'Centro',
			'street' => 'Rua 20',
			'number' => '736',
			'complement' => 'Casa',
			'status' => 1,
			'role' => 'seller',
			'parent_id' => 1
		]);
		$repository->create([
			'name' => 'Daniel Wietholter',
			'username' => 'daniel.wietholter',
			'password' => '12345',
			'email' => 'daniwl.w@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => $faker->cpf,
			'gender' => 'male',
			'birthdate' => '2000-08-07',
			'zipcode' => '95890000',
			'state' => 'RS',
			'city' => 'Teutônia',
			'district' => 'Centro',
			'street' => 'Rua 20',
			'number' => '735',
			'complement' => 'Casa',
			'status' => 1,
			'role' => 'seller',
			'parent_id' => 2
		]);
		$repository->create([
			'name' => 'Beatriz Wietholter',
			'username' => 'beatriz.wietholter',
			'password' => '12345',
			'email' => 'beatriz.w@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => $faker->cpf,
			'gender' => 'female',
			'birthdate' => '2000-08-07',
			'zipcode' => '95890000',
			'state' => 'RS',
			'city' => 'Teutônia',
			'district' => 'Centro',
			'street' => 'Rua 20',
			'number' => '735',
			'complement' => 'Casa',
			'status' => 1,
			'role' => 'partner',
			'parent_id' => 2
		]);
		$repository->create([
			'name' => 'Adelir Schneider',
			'username' => 'adelir.schneider',
			'password' => '12345',
			'email' => 'adelir.s@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => $faker->cpf,
			'gender' => 'male',
			'birthdate' => '2000-08-07',
			'zipcode' => '95890000',
			'state' => 'RS',
			'city' => 'Teutônia',
			'district' => 'Centro',
			'street' => 'Rua 20',
			'number' => '735',
			'complement' => 'Casa',
			'status' => 1,
			'role' => 'seller',
			'parent_id' => 4
		]);
		$repository->create([
			'name' => 'Odário Pfeifenberg',
			'username' => 'odario',
			'password' => '12345',
			'email' => 'odario@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => $faker->cpf,
			'gender' => 'male',
			'birthdate' => '2000-08-07',
			'zipcode' => '95890000',
			'state' => 'RS',
			'city' => 'Teutônia',
			'district' => 'Centro',
			'street' => 'Rua 20',
			'number' => '735',
			'complement' => 'Casa',
			'status' => 1,
			'role' => 'seller',
			'parent_id' => 2
		]);

		foreach(range(1,15) as $k){
			factory(App\Models\User::class, 1)->create()->each(function($user) use($repository){
				$users = $repository->all();
				$parent = $users->random();
				$user->parent()->associate($parent);
				$user->save();
			});
		}
	}
}
