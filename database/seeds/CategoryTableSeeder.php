<?php

use Illuminate\Database\Seeder;
use App\Repositories\CategoryRepository;

class CategoryTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		$repository = app(CategoryRepository::class);
		$repository->create(['name' => 'Colchões']);
		$repository->create(['name' => 'Travesseiros']);
		$repository->create(['name' => 'Aparelhos']);
		$repository->create(['name' => 'Esteiras']);
		$repository->create(['name' => 'Demais']);
	}
}
