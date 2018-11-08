<?php

use Illuminate\Database\Seeder;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\ItemRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ProductRepository;
use App\Repositories\BillRepository;

class OrderTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
		$faker = Faker\Factory::create('pt_BR');
		$orderRepository = app(OrderRepository::class);
		$userRepository = app(UserRepository::class);
		$itemRepository = app(ItemRepository::class);
		$taskRepository = app(TaskRepository::class);
		$productRepository = app(ProductRepository::class);
		$billRepository = app(BillRepository::class);
		foreach(range(1,55) as $k){
			// Create Order
			factory(App\Models\Order::class, 1)->create([
				'status_id' => '3',
				'payment_method' => 'billet',
				'payment_link' => 'https://sandbox.pagseguro.uol.com.br/checkout/payment/booklet/print.jhtml?c=b5b1a75226108a513def770c3b3cba30e3ad95b8cad6409f79b5ffd8bea24a93b335941111f905d0',
				'payment_brief' => '{\"free_1\":{\"quantity\":1,\"installment\":109.2,\"total\":109.2}}',
				'card_id' => NULL,
				'session' => '2aabb35ca59947e7a6d25f14ac50c093',
			])->each(function($order) use($userRepository, $taskRepository, $productRepository, $faker){
				$users = $userRepository->all();
				$client = $users->random();

				$order->client()->associate($client);
				$order->zipcode = $client->zipcode;
				$order->state = $client->state;
				$order->city = $client->city;
				$order->district = $client->district;
				$order->street = $client->street;
				$order->number = $client->number;
				$order->complement = $client->complement;
				$order->save();

				// Create Task
				$taskRepository->create([
					'order_id' => $order->id,
					'stage_id' => '1'
				]);

				// Create Items
				$quant = $faker->numberBetween(1, 5);
				$products = $productRepository->all();
				$total = 0.0;
				for ($i=0; $i < $quant; $i++) {
					$product = $products->random();
					factory(App\Models\Item::class, 1)->create([
						'order_id' => $order->id,
						'product_id' => $product->id,
						'quantity' => '1',
						'value' => $client->role == USER_ROLES_ADMIN || $client->role == USER_ROLES_SELLER ? $product->value_seller : $product->value_partner,
						'interest_free' => $product->interest_free,
						'free_shipping' => $product->free_shipping,
						'delivery_form' => '1',
						'delivery_cost' => $product->free_shipping == 1 ? '0' : $faker->randomFloat(2, 10, 50),
						'delivery_time' => '5',
						'delivery_methods' => '[{"codigo":2,"prazo":1,"valor":22.3},{"codigo":1,"prazo":5,"valor":20.7},{"codigo":0,"prazo":0,"valor":0}]',
						'payment_installments' => '1'
					])->each(function($item) use($order, $total){
						// Update order payment
						$total += $item->value+$item->delivery_cost;
						$order->payment_brief = '{"free_1":{"quantity":1,"installment":'.$total.',"total":'.$total.'}}';
						$order->save();
					});
				}
			});
		}

		$stages = [STAGE_PAYMENT, STAGE_SEND, STAGE_DELIVERY];

		foreach ($stages as $stage) {
			$tasks = $taskRepository->findWhere(['stage_id' => $stage, 'date_conclusion' => null]);
			foreach ($tasks as $task) {
				if($faker->numberBetween(1, 10) <= 7){
					$task = App\Models\Task::find($task->id);
					$task->date_conclusion = date('Y-m-d H:i:s');
					$task->user_id = 1;
					$task->save();

					$taskNew = App\Models\Task::find($task->id);
					$order = App\Models\Order::find($task->order_id);
					$order->status_id = $taskNew->stage->status_id;
					$order->save();
					if($taskNew->stage->next_stage_id != null){
						$taskRepository->create([
							"order_id" => $taskNew->order_id,
							"stage_id" => $taskNew->stage->next_stage_id
						]);
					}

					switch ($task->stage_id) {
						case STAGE_PAYMENT:
							$billRepository->generateBills($task->order);
							break;
					}
				}
			}
		}

	}
}
