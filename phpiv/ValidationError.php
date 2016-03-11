<?php

namespace Phpiv;

class ValidationError extends \Exception {
	protected $errors = array();

	public function setErrors(array $errors) {
		$this->errors = $errors;
	}

	public function getErrors() {
		return $this->errors;
	}

}
