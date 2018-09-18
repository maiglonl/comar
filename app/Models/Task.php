<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Task.
 *
 * @package namespace App\Models;
 */
class Task extends Model implements Transformable {
	use TransformableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'order_id',
		'stage_id',
		'date_conclusion'
	];

	protected $with = ['order', 'user'];

	public function order(){
		return $this->belongsTo(Order::class);
	}

	public function stage(){
		return $this->belongsTo(Stage::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

}
