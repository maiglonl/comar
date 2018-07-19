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
			'name' => 'required|string|max:100',
			'username' => 'required|string|max:20|unique:users',
			'cp' => 'required|string|max:18|unique:users',
			'email' => 'required|string|email|max:100|unique:users',
			'phone1' => 'required|max:11',
			'phone2' => 'max:11',
			'gender' => 'required|max:6',
			'zipcode' => 'required|max:9',
			'state' => 'required|max:25',
			'city' => 'required|max:100',
			'district' => 'required|max:100',
			'street' => 'required|max:150',
			'number' => 'required',
			'complement' => 'max:150',
			'role' => 'required',
			'parent_id' => 'exists:users,id'
		],
		ValidatorInterface::RULE_UPDATE => [
			'name' => 'required|string|max:100',
			'username' => 'required|string|max:20',
			'cp' => 'required|string|max:18',
			'email' => 'required|string|email|max:100',
			'phone1' => 'required|max:11',
			'phone2' => 'max:11',
			'gender' => 'required|max:6',
			'zipcode' => 'required|max:9',
			'state' => 'required|max:25',
			'city' => 'required|max:100',
			'district' => 'required|max:100',
			'street' => 'required|max:150',
			'number' => 'required',
			'complement' => 'max:150',
			'role' => 'required',
			'parent_id' => 'exists:users,id'
		],
	];
}
