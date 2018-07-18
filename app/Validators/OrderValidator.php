<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class OrderValidator.
 *
 * @package namespace App\Validators;
 */
class OrderValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
		'user_id' => 'required|exists:users,id',
		'status_id' => 'required|exists:users,id',
		'zipcode' => 'required',
		'state' => 'required|size:2',
		'city' => 'required',
		'district' => 'required',
		'street' => 'required',
		'number' => 'required'
	];
}
