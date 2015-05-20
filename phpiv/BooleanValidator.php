<?php

class BooleanValidator extends Validator {

	public function baseCheck() {
		if(!$this->isNull && !is_bool($this->value)) {
			return array('El valor recibido no es válido');
		}
		return array();
	}
}

?>
