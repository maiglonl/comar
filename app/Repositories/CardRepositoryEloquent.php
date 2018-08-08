<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CardRepository;
use App\Models\Card;
use App\Validators\CardValidator;

/**
 * Class CardRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CardRepositoryEloquent extends BaseRepository implements CardRepository{
	/**
	 * Specify Model class name
	 */
	public function model(){
		return Card::class;
	}

	/**
	 * Specify Validator class name
	 */
	public function validator(){
		return CardValidator::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}
	
}
