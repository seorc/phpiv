<?php

require_once 'autoload.php';

class ValidatorSet {
	protected $validators = array();
	protected $types = array(
		'basic' => 'Validator',
		'string' => 'StringValidator',
		'date' => 'DateValidator',
		'email' => 'EmailValidator',
		'number' => 'NumberValidator',
	);
	public function add($type, $codename, $name='') {
		if(is_string($type) && array_key_exists($type, $this->types)) {
			$class = $this->types[$type];
			$v = new $class($codename, $name);
			$this->validators[$codename] = $v;
			return $v;
		}
		elseif(class_exists($type)) {
			$v = new $type($codename, $name);
			if(!is_a($v, 'Validator')) {
				throw new InvalidArgumentException(
					'The class must be a validator');
			}
			$this->validators[$codename] = $v;
			return $v;
		}
		throw new InvalidArgumentException(
			'You must pas a validator class or its alias');
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
}

?>
