<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ItemRepository;
use App\Validators\ItemValidator;
use App\Presenters\ItemPresenter;
use App\Models\Item;

/**
 * Class ItemRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ItemRepositoryEloquent extends BaseRepository implements ItemRepository{
	/**
	 * Specify Model class name
	 */
	public function model(){
		return Item::class;
	}

	/**
	 * Specify Validator class name
	 */
	public function validator(){
		return ItemValidator::class;
	}

	// public function presenter(){
	// 	return ItemPresenter::class;
	// }

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * Create Repository
	 */
	public function create(Array $attributes){
		$item = $this->findWhere(['order_id' => $attributes['order_id'], 'product_id' => $attributes['product_id']])->first();
		if($item){
			$item->quantity++;
			return parent::update($item->toArray(), $item->id);
		}else{
			$attributes['quantity'] = 1;
			return parent::create($attributes);
		}
	}	
}
