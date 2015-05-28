<?php

class NumberValidator extends Validator {
	public function baseCheck() {
		$errors = array();
		if(!$this->isEmpty && !is_numeric($this->value)) {
			$errors[] = 'Debe ser un número';
		}
		return $errors;
	}

	protected function clean() {
		return (int) $this->value;
	}
}

?>
