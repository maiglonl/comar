<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CategoryValidator.
 *
 * @package namespace App\Validators;
 */
class CategoryValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
		'name' => 'required|string|max:255'
	];
}
