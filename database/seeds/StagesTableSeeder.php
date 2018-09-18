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

		$repository->create(['id' => 3,'name' => 'Entrega', 'next_stage_id' => null,'status_id' => STATUS_ORDER_ETREGUE]);
		$repository->create(['id' => 2,'name' => 'Envio', 'next_stage_id' => 3,'status_id' => STATUS_ORDER_ENV]);
		$repository->create(['id' => 1,'name' => 'Pagamento', 'next_stage_id' => 2,'status_id' => STATUS_ORDER_AG_ENV]);
	}
}
