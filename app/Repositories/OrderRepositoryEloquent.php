<?php

namespace App\Repositories;

use Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Validators\OrderValidator;

/**
 * Class OrderRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository{
	/**
	 * Specify Model class name
	 */
	public function model(){
		return Order::class;
	}

	/**
	 * Specify Validator class name
	 */
	public function validator(){
		return OrderValidator::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * Return current open order or create a new.
	 */
	public function current(){
		$order = $this->findWhere(['user_id' => Auth::id(), 'status_id' => STATUS_ORDER_EM_ABERTO])->first();
		if(!$order){
			$order = $this->create(['user_id' => Auth::id(), 'status_id' => STATUS_ORDER_EM_ABERTO]);
		}
		return $order;
	}

}
