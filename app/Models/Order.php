<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\Item;

/**
 * Class Order.
 *
 * @package namespace App\Models;
 */
class Order extends Model implements Transformable {
	use TransformableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'status_id',
		'zipcode',
		'state',
		'city',
		'district',
		'street',
		'number',
		'complement',
		'payment_method',
		'delivery_form'
	];

	protected $with = ['items'];

	public function items(){
		return $this->hasMany(Item::class);
	}

}
