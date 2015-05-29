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
		if(array_key_exists('max', $this->v) && $this->v['max'] < $min) {
			throw new InvalidArgumentException('Min cannot be greater than max');
		}
		$this->v['min'] = $min;	
		return $this;
	}

	public function max($max) {
		if(!is_numeric($max)) {
			throw new InvalidArgumentException('You must pass a number to this method');
		}
		if(array_key_exists('min', $this->v) && $this->v['min'] > $max) {
			throw new InvalidArgumentException('Max cannot be less than min');
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

	/**
	 * Convenience method to set min-max in a single step.
	 *
	 * This method is chainable.
	 */
	public function between($min, $max) {
		return $this->min($min)->max($max);
	}
}

?>
