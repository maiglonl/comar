<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 *
 * @package namespace App\Models;
 */
class User extends Authenticatable implements Transformable{
	use Notifiable;
	use TransformableTrait;

	protected $fillable = [
		'id',
		'name',
		'username',
		'password',
		'email',
		'cp',
		'phone1',
		'phone2',
		'gender',
		'birthdate',
		'zipcode',
		'state',
		'city',
		'district',
		'street',
		'number',
		'complement',
		'status',
		'role',
		'parent_id'
	];

	protected $hidden = ['password', 'remember_token'];
	protected $with = [];
	//protected $appends = ['search'];

	public function childrens(){
		return $this->hasMany(User::class, 'parent_id');
	}

	public function parent(){
		return $this->belongsTo(User::class, 'parent_id');
	}

	public function getSearchAttribute(){
		return "$this->id - $this->name [$this->city]";
	}
}
