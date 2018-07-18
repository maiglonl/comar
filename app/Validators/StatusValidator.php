<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class StatusValidator.
 *
 * @package namespace App\Validators;
 */
class StatusValidator extends LaravelValidator {
	/**
	 * Validation Rules
	 *
	 * @var array
	 */
	protected $rules = [
		ValidatorInterface::RULE_CREATE => [],
		ValidatorInterface::RULE_UPDATE => [],
	];
}
