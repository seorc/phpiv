<?php

class EmailValidator extends StringValidator {
	protected $email_ereg = '/^[a-z0-9._\-]+@[a-z0-9\-]+(\.[a-z0-9\-]+)+$/';
	

	public function baseCheck() {
		$errors = array();
		if(!$this->isEmpty && !preg_match($this->email_ereg, $this->value)) {
			$errors[] = 'No es un email válido';
		}
		return $errors;
	}

	protected function clean() {
		return trim($this->value);
	}
}

?>
