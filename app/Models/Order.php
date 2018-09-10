<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Helpers\PermHelper;
use App\Models\Item;
use App\Models\User;
use App\Models\Status;

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
		'payment_link',
		'card_id',
		'session'
	];

	protected $with = ['items', 'client', 'card', 'status'];
	protected $appends = ['total', 'total_items', 'total_delivery', 'payment_groups'];

	public function getTotalAttribute(){
		return $this->total_delivery + $this->total_items;
	}

	public function getTotalItemsAttribute(){
		$result = 0;
		foreach ($this->items as $item) {
			$result += $item['value'] * $item['quantity'];
		}
		return $result;
	}

	public function getTotalDeliveryAttribute(){
		$result = 0;
		foreach ($this->items as $item) {
			$result += $item['delivery_cost'] * $item['quantity'];
		}
		return $result;
	}

	public function getPaymentGroupsAttribute(){
		$result = [];
		if($this->payment_method != PAYMENT_METHOD_CREDIT_CARD){
			return array("free_1" => [
				'items' => $this->items,
				'total' => $this->total,
				'installments' => [[
					'quantity' => 1,
					'totalAmount' => $this->total,
					'installmentAmount' => $this->total,
					'interestFree' => true
				]],
				'selected' => 1
			]);
		}
		foreach ($this->items as $item) {
			$index = "free_".$item['interest_free'];
			error_log($index." -> ".$item['total']);
			if(!isset($result[$index])){
				$result[$index] = [
					'items' => [],
					'total' => 0,
					'installments' => [],
					'selected' => 1
				];
			}
			$result[$index]['items'][] = $item;
			$result[$index]['total'] += $item['total'];
			$result[$index]['installments'] = [[
				'quantity' => 1,
				'totalAmount' => $item['total'],
				'installmentAmount' => $item['total'],
				'interestFree' => true
			]];
			$result[$index]['selected'] = $item['payment_installments'];
		}
		return $result;
	}

	public function items(){
		return $this->hasMany(Item::class);
	}

	public function client(){
		return $this->belongsTo(User::class, 'user_id');
	}

	public function card(){
		return $this->belongsTo(Card::class, 'card_id');
	}

	public function status(){
		return $this->belongsTo(Status::class, 'status_id');
	}

}
