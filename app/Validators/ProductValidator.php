<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ProductValidator.
 *
 * @package namespace App\Validators;
 */
class ProductValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
		'name' => 'required|string|max:255',
		'description' => 'required',
		'category_id' => 'required|exists:categories,id',
		'value_partner' => 'required',
		'value_seller' => 'required',
		'weight' => 'required',
		'height' => 'required',
		'width' => 'required',
		'length' => 'required',
		'diameter' => 'required',
		'quantity' => 'required',
		'status' => 'required'
	];
}
