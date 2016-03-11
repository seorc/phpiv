<?php

namespace Phpiv;

use InvalidArgumentException;

class ValidatorSet {

	protected $validators = array();
	protected $types = array(
		'basic' => 'Phpiv\Validator',
		'string' => 'Phpiv\StringValidator',
		'date' => 'Phpiv\DateValidator',
		'email' => 'Phpiv\EmailValidator',
		'number' => 'Phpiv\NumberValidator',
		'bool' => 'Phpiv\BooleanValidator',
	);

	/**
	 * Add a validator to this set.
	 *
	 * You must pass either a Validator class name or an alias according by
	 * this class' $tyes attribute.
	 */
	public function add($type, $codename, $name='') {
		if(is_string($type) && array_key_exists($type, $this->types)) {
			$class = $this->types[$type];
			$v = new $class($codename, $name);
			$this->validators[$codename] = $v;
			return $v;
		}
		elseif(class_exists($type)) {
			if(!is_subclass_of($type, 'Phpiv\Validator')) {
				throw new InvalidArgumentException(
					'The class must be a validator');
			}
			$v = new $type($codename, $name);
			$this->validators[$codename] = $v;
			return $v;
		}
		throw new InvalidArgumentException(
			'You must pas a validator class or its alias');
	}

	/**
	 * Get a validator from this set.
	 */
	public function get($codename) {
		return $this->validators[$codename];
	}

	public function check($data) {
		$errors = array();
		foreach($this->validators as $v) {
			try {
				$v->check($data);
			}
			catch(ValidationError $e) {
				$errors[$v->codename] = $e->getErrors();
			}
		}
		if(count($errors) > 0) {
			$e = new ValidationError("Hay errores en los datos");
			$e->setErrors($errors);
			throw $e;
		}
	}

	/**
	 * Obtain an array with the cleaned value of each Validator this set
	 * contains.
	 */
	public function getCleaned() {
		$cleaned = array();
		foreach($this->validators as $v) {
			$cleaned[$v->codename] = $v->cleanedValue;
		}
		return $cleaned;
	}
}
