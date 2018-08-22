<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ItemValidator.
 *
 * @package namespace App\Validators;
 */
class ItemValidator extends LaravelValidator{
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
		'order_id' => 'required|exists:orders,id',
		'product_id' => 'required|exists:products,id',
		'quantity' => 'required|min:1',
		'value' => 'required|min:1'
	];
}
