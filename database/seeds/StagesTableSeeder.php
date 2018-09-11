<?php

use Illuminate\Database\Seeder;
use App\Repositories\StatusRepository;

class StagesTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		$repository = app(StatusRepository::class);
		$repository->create(['name' => 'Em aberto', 'type' => 'order']);
		$repository->create(['name' => 'Cancelado', 'type' => 'order']);
		$repository->create(['name' => 'Aguardando pagamento', 'type' => 'order']);
		$repository->create(['name' => 'Pagamento aprovado', 'type' => 'order']);
		$repository->create(['name' => 'Aguardando envio', 'type' => 'order']);
		$repository->create(['name' => 'Envio realizado', 'type' => 'order']);
		$repository->create(['name' => 'Entregue', 'type' => 'order']);
	}
}
