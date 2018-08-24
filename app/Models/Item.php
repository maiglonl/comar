<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\Product;
use App\Helpers\PermHelper;

/**
 * Class Item.
 *
 * @package namespace App\Models;
 */
class Item extends Model implements Transformable {
	use TransformableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'order_id',
		'product_id',
		'quantity',
		'value',
		'interest_free',
		'free_shipping',
		'delivery_form',
		'delivery_cost',
		'delivery_time',
		'delivery_methods',
		'installments_available',
		'payment_installments',
		'payment_installment'
	];

	protected $with = ['product'];
	protected $appends = ['delivery_availables', 'total'];

	public function product(){
		return $this->belongsTo(Product::class);
	}

	public function getDeliveryAvailablesAttribute(){
		return $this->delivery_methods == null ? null : json_decode($this->delivery_methods);
	}

	public function getTotalAttribute(){
		if($this->payment_installments > 0 && $this->payment_installment > 0){
			return $this->payment_installments * $this->payment_installment * $this->quantity;
		}else{
			return ($this->value + $this->delivery_cost) * $this->quantity;
		}
	}


}
