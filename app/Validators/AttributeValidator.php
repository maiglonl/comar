<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class AttributeValidator.
 *
 * @package namespace App\Validators;
 */
class AttributeValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
        'name' => 'required|string|max:255|min:3',
        'value' => 'required|string|max:255',
        'product_id' => 'required|exists:products,id'
	];
}
