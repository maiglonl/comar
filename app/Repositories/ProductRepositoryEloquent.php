<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Validators\ProductValidator;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepositoryEloquent extends BaseRepository implements ProductRepository{

	protected $fieldSearchable = [
		'name' => 'like',
		'description' => 'like',
		'categorie.name' => 'like'
	];

	/**
	 * Specify Model class name
	 */
	public function model(){
		return Product::class;
	}

	/**
	 * Specify Validator class name
	 */
	public function validator(){
		return ProductValidator::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}
	
}
