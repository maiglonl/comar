<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Repositories\TaskRepository;

/**
 * Class Stage.
 *
 * @package namespace App\Models;
 */
class Stage extends Model implements Transformable{
	use TransformableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 
		'status_id',
		'next_stage_id'
	];

	protected $with = [];

	public function open_tasks(){
		return $this->hasMany(Task::class, 'stage_id', 'id')->whereNull('date_conclusion');
	}

}
