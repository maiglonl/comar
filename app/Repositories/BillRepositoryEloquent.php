<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BillRepository;
use App\Models\Bill;
use App\Models\User;
use App\Validators\BillValidator;
use App\Helpers\DateHelper;
use Auth;

/**
 * Class BillRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class BillRepositoryEloquent extends BaseRepository implements BillRepository{
	/**
	 * Specify Model class name
	 */
	public function model(){
		return Bill::class;
	}

	/**
	 * Specify Validator class name
	 */
	public function validator(){
		return BillValidator::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function generateBills($order){
		// Gera conta a receber do valor do pedido
		$bill = [
			'name' => 'Recibo de venda',
			'type' => 'credit',
			'date_due' => DateHelper::addDate(date('Y-m-d'), 'P1M'),
			'value' => $order->net_value,
			'done' => false,
			'order_id' => $order->id
		];
		$this->create($bill);
		// Para cada Comissao, se houver usuário pai, registrar conta a pagar
		$rates = [0.1, 0.08, 0.06, 0.04, 0.02];
		$user_parent = User::find($order->client['parent_id']);
		error_log($user_parent);
		error_log("0");
		foreach($rates as $rate){
			error_log("1");
			if(!isset($user_parent) || !$user_parent || $user_parent == null){
				break;
				error_log("1.1");
			}
			error_log("2");
			$bill = [
				'name' => 'Pagamento de comissão',
				'type' => 'debit',
				'date_due' => DateHelper::addDate(date('Y-m-d'), 'P1M'),
				'value' => (float)$order->total_items*$rate,
				'done' => false,
				'order_id' => $order->id,
				'user_id' => $user_parent->id
			];
			error_log("3");
			$this->create($bill);
			error_log("4");
			$user_parent = User::find($user_parent->parent_id);
			error_log("4");
		}
		error_log("5");
		return response(200);
	}
	
}
