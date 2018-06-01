<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class UserValidator.
 *
 * @package namespace App\Validators;
 */
class UserValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
		ValidatorInterface::RULE_CREATE => [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users',
            'cp' => 'required|string|max:18|unique:users',
            'email' => 'required|string|email|max:255|unique:users'
		],
		ValidatorInterface::RULE_UPDATE => [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20',
            'email' => 'required|string|email|max:255'
		],
	];
}
