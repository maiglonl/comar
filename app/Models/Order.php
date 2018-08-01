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
		'payment_installments',
		'payment_installment'
	];

	protected $with = ['items'];
	protected $appends = ['total', 'total_items', 'total_delivery'];

	public function getTotalAttribute(){
		return $this->total_delivery + $this->total_items;
	}

	public function getTotalItemsAttribute(){
		$result = 0;
		foreach ($this->items as $item) {
			if($item['payment_installments'] > 0 && $item['payment_installment'] > 0){
				$result += $item['payment_installments'] * $item['payment_installment'];
			}else{
				$result += $item['product'][PermHelper::lowerValueText()] * $item['amount'];
			}
		}
		return $result;
	}

	public function getTotalDeliveryAttribute(){
		$result = 0;
		foreach ($this->items as $item) {
			$result += $item['delivery_cost']*$item['amount'];
		}
		return $result;
	}

	public function items(){
		return $this->hasMany(Item::class);
	}

}
