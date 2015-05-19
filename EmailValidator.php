<?php

require_once 'autoload.php';

class EmailValidator extends Validator {
	protected $email_ereg = '/^[a-z0-9._\-]+@[a-z0-9\-]+(\.[a-z0-9\-]+)+$/';
	

	public function baseCheck() {
		$errors = array();
		if($this->value && !preg_match($this->email_ereg, $this->value)) {
			$errors[] = 'No es un email válido';
		}
		return $errors;
	}
}

?>
