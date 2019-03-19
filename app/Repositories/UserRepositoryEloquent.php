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
	 */
	public function model(){
		return User::class;
	}

	/**
	* Specify Validator class name
	*/
	public function validator(){
		return UserValidator::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot(){
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * Create Repository
	 */
	public function create(array $attributes){
		$attributes["password"] = isset($attributes["password"]) ? bcrypt($attributes["password"]) : bcrypt("12345");
		if(!isset($attributes["id"])){
			$attributes["id"] = rand(1000, 9999);
			$user = User::find($attributes["id"]);
			while($user){
				$attributes["id"] = rand(1000, 9999);
				$user = User::find($attributes["id"]);
			}
		}
		return parent::create($attributes);
	}

	/**
	 * Update Repository
	 */
	public function update(array $attributes, $id){
		if(isset($attributes["id"])){
			$attributes["password"] = bcrypt($attributes["password"]);
		}
		return parent::update($attributes, $id);
	}
}
