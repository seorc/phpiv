<?php

class DateValidator extends Validator {

	protected $formatUsed;

	/**
	 * Validate the format of the date.
	 */
	public function format(array $format) {
		$this->v['format'] = $format;
	}

	/**
	 * Makes a call to DateTime::createFromFormat() to execute the validation,
	 * so you must use a format which comply with that method's specification.
	 *
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 */
	public function formatCheck(array $data) {
		$val = $this->value;
		if($val && is_null($this->clean())) {
			return 'El formato es incorrecto';
		}
	}

	/**
	 * Validate a mimimum date.
	 *
	 * $date must be written in ISO format or a DateTime instance must be 
	 * passed.
	 */
	public function min($date) {
		if(!is_a($date, 'DateTime')) {
			$date = DateTime::createFromFormat('Y-m-d', $date);
			if(!$date) {
				throw new InvalidArgumentException(
					'The date must be a ISO-formated string or a DateTime instance');
			}
		}
		if(array_key_exists('max', $this->v) && $this->v['max'] < $date) {
			throw new InvalidArgumentException(
				'Min date must not be posterior to max date');
		}
		$this->v['min'] = $date;
		return $this;
	}

	public function max($date) {
		if(!is_a($date, 'DateTime')) {
			$date = DateTime::createFromFormat('Y-m-d', $date);
			if(!$date) {
				throw new InvalidArgumentException(
					'The date must be a ISO-formated string or a DateTime instance');
			}
		}
		if(array_key_exists('min', $this->v) && $this->v['min'] > $date) {
			throw new InvalidArgumentException(
				'Max date must not be previous to min date');
		}
		$this->v['max'] = $date;
		return $this;
	}

	/**
	 * Convenience method to define min and max boundaries in a signle call.
	 */
	public function between($min, $max) {
		return $this->min($min)->max($max);
	}

	public function minCheck(array $data) {
		if($this->value && $this->clean()) {
			if($this->cleanedValue < $this->v['min']) {
				return sprintf("No debe ser anterior a %s",
					$this->v['min']->format($this->formatUsed));
			}
		}
	}

	public function maxCheck(array $data) {
		if($this->value && $this->clean()) {
			if($this->cleanedValue > $this->v['max']) {
				return sprintf("No debe ser posterior a %s",
					$this->v['max']->format($this->formatUsed));
			}
		}
	}

	protected function clean() {
		if(!is_null($this->cleanedValue)) {
			return $this->cleanedValue;
		}
		if(in_array('format', $this->v)) {
			$format = $this->v['format'];
		}
		else {
			$format = array('Y-m-d');
		}
		foreach($format as $fmt) {
			$parsed = DateTime::createFromFormat($fmt, $this->value);
			if($parsed) {
				$this->formatUsed = $fmt;
				return $this->cleanedValue = $parsed;
			}
		}
		return null;
	}
}

?>
