<?php

use Illuminate\Database\Seeder;
use App\Repositories\StageRepository;

class StagesTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		$repository = app(StageRepository::class);

		$repository->create([
			'id' => 5,
			'name' => 'Remunerações', 
			'description' => 'Realizar o pagamento das remunerações',
			'next_stage_id' => null,
			'status_id' => STATUS_ORDER_ETREGUE
		]);
		$repository->create([
			'id' => 4,
			'name' => 'Crédito', 
			'description' => 'Confirmar crédito do valor do pedido na conta',
			'next_stage_id' => 5,
			'status_id' => STATUS_ORDER_ETREGUE
		]);
		$repository->create([
			'id' => 3,
			'name' => 'Entrega', 
			'description' => 'Confirmar entrega do pedito',
			'next_stage_id' => 4,
			'status_id' => STATUS_ORDER_ETREGUE
		]);
		$repository->create([
			'id' => 2,
			'name' => 'Envio', 
			'description' => 'Realizar envio do pedido',
			'next_stage_id' => 3,
			'status_id' => STATUS_ORDER_ENV
		]);
		$repository->create([
			'id' => 1,
			'name' => 'Pagamento', 
			'description' => 'Confirmar pagamento do pedido',
			'next_stage_id' => 2,
			'status_id' => STATUS_ORDER_AG_ENV
		]);
	}
}
