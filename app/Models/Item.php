<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\Product;

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
		'amount',
		'value',
		'interest_free',
		'free_shipping'
	];

	protected $with = ['product'];

	public function product(){
		return $this->belongsTo(Product::class);
	}

}
