<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository {
	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	public function model(){
		return User::class;
	}

	/**
	* Specify Validator class name
	*
	* @return mixed
	*/
	public function validator(){
		return UserValidator::class;
	}


	/**
	 * Create Repository
	 * @param  array  $attributes [description]
	 * @return Repository         [description]
	 */
	public function create(array $attributes){
		$attributes["password"] = isset($attributes["password"]) ? bcrypt($attributes["password"]) : bcrypt("12345");
		return parent::create($attributes);
	}

	/**
	 * Update Repository
	 * @param  array  $attributes [description]
	 * @return Repository         [description]
	 */
	public function update(array $attributes, $id){
		if(isset($attributes["password"])){
			$attributes["password"] = bcrypt($attributes["password"]);
		}
		return parent::update($attributes, $id);
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}
	
}
