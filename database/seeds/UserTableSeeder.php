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
			'password' => '948571',
			'email' => 'maiglonl@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => '111.111.111-11',
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
			'name' => 'Lucas Wietholter',
			'username' => 'lucas',
			'password' => '948571',
			'email' => 'lucasw@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => '025.579.611-27',
			'gender' => 'male',
			'birthdate' => '2003-11-26',
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
			'name' => 'Fulana da Silva',
			'username' => 'fulana',
			'password' => '948571',
			'email' => 'fulana@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => '025.579.612-27',
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
			'name' => 'Beltrana da Silva',
			'username' => 'beltrana',
			'password' => '948571',
			'email' => 'beltrana@gmail.com',
			'phone1' => '51997398991',
			'phone2' => '51997394125',
			'cp' => '025.579.613-27',
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
	}
}
