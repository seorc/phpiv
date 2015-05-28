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

	public function min($min) {
		if(!is_numeric($min)) {
			throw InvalidArgumentException('You must pass a number to this method');
		}
		$this->v['min'] = $min;	
		return $this;
	}

	public function max($max) {
		if(!is_numeric($max)) {
			throw new InvalidArgumentException('You must pass a number to this method');
		}
		$this->v['max'] = $max;	
		return $this;
	}

	public function minCheck(array $data) {
		if(!$this->isEmpty && $this->value < $this->v['min']) {
			return "No puede ser menor de {$this->v['min']}";
		}
	}

	public function maxCheck(array $data) {
		if(!$this->isEmpty && $this->value > $this->v['max']) {
			return "No puede ser mayor de {$this->v['max']}";
		}
	}
}

?>
