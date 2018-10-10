<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Models\User;
use App\Models\Order;

/**
 * Class Bill.
 *
 * @package namespace App\Models;
 */
class Bill extends Model implements Transformable {
	use TransformableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'name',
		'type',
		'description',
		'date_due',
		'value',
		'tax',
		'done',
		'order_id',
		'user_id'
	];

	protected $with = ['user', 'order'];

	public function user(){
		return $this->belongsTo(User::class, 'user_id');
	}

	public function order(){
		return $this->belongsTo(Order::class, 'order_id');
	}

}
