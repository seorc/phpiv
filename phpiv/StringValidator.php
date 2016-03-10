<?php

/**
 * Validator of dates.
 *
 * This validator expects an ISO formated date by default.
 */
class StringValidator extends Validator {

	public function maxLength($val) {
		if(!is_numeric($val) || $val < 1) {
			throw new InvalidArgumentException('Must be a positive number');
		}
		if(array_key_exists('minLength', $this->v) && $this->v['minLength'] > $val) {
			throw new InvalidArgumentException(
				'You have set a minLength which is greather than maxLength');
		}
		$this->v['maxLength'] = $val;
		return $this;
	}

	public function minLength($val) {
		if(!is_numeric($val) || $val < 1) {
			throw new InvalidArgumentException('Must be a positive number');
		}
		if(array_key_exists('maxLength', $this->v) && $this->v['maxLength'] < $val) {
			throw new InvalidArgumentException(
				'You have set a maxLength which is smaller than minLength');
		}
		$this->v['minLength'] = $val;
		return $this;
	}

	public function choices(array $choices) {
		if(count($choices) == 0) {
			throw new InvalidArgumentException('At least one choice required');
		}
		$this->v['choices'] = $choices;
		return $this;
	}

	public function length($min=null, $max=null) {
		if(is_null($min) && is_null($max)) {
			throw new InvalidArgumentException('You must specify a min and/or max');
		}
		if(!is_null($min)) {
			$this->minLength($min);
		}
		if(!is_null($max)) {
			$this->maxLength($max);
		}
		else {
			$this->maxLength($min);
		}
		return $this;
	}

	public function maxLengthCheck(array $data) {
		$val = $this->value;
		if($val != '' && strlen($val) > $this->v['maxLength']) {
			return sprintf('Debe tener %d caracteres como máximo',
				$this->v['maxLength']);
		}
	}

	public function minLengthCheck(array $data) {
		$val = $this->value;
		if($val != '' && strlen($val) < $this->v['minLength']) {
			return sprintf('Debe tener %d caracteres como mínimo',
				$this->v['minLength']);
		}
	}

	public function choicesCheck(array $data) {
		$val = $this->value;
		if($val && !in_array($val, $this->v['choices'])) {
			return sprintf('Opción inválida');
		}
	}
}

?>
