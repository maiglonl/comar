<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Helpers\PermHelper;
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
	protected $appends = ['total', 'total_items', 'total_delivery'];

	public function getTotalAttribute(){
		return $this->total_delivery + $this->total_items;
	}

	public function getTotalItemsAttribute(){
		$result = 0;
		foreach ($this->items as $item) {
			$result += $item['product'][PermHelper::lowerValueText()];
		}
		return $result;
	}

	public function getTotalDeliveryAttribute(){
		$result = 0;
		foreach ($this->items as $item) {
			$result += $item['product']['free_shipping'] == 1 ? $item['delivery_cost'] : 0;
		}
		return $result;
	}

	public function items(){
		return $this->hasMany(Item::class);
	}

}
