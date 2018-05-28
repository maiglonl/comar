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
		$repository = app(UserRepository::class);
		$repository->create([
			'name' => 'Maiglon Lubacheuski',
			'username' => 'maiglon',
			'password' => bcrypt('948571'),
			'email' => 'maiglonl@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
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
	}
}
