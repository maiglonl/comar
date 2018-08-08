<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CardValidator.
 *
 * @package namespace App\Validators;
 */
class CardValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
        'number' => 'required',
        'name' => 'required|string|max:255|min:3',
        'date_due' => 'required',
        'code' => 'required',
        'user_id' => 'required|exists:users,id'
	];
}
