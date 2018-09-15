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

		$repository->create(['name' => 'Pagamento', 'status_id' => STATUS_ORDER_AG_ENV]);
		$repository->create(['name' => 'Envio', 'status_id' => STATUS_ORDER_ENV]);
		$repository->create(['name' => 'Entrega', 'status_id' => STATUS_ORDER_ETREGUE]);
	}
}
