<?php

require_once 'ValidationError.php';

class Validator {

	protected $v;
	public $name;
	public $codename;
	protected $value;
	protected $isEmpty;
	protected $cleanedValue = null;

	public function __construct($codename, $name='') {
		$this->name = $name ? $name : $codename;
		$this->codename = $codename;
		$this->v = array();
	}

	public function required($req = true) {
		if(!is_bool($req)) {
			throw new InvalidArgumentException('Must be boolean');
		}
		$this->v['required'] = $req;
		return $this;
	}

	public function requiredCheck(array $data) {
		if(is_null($this->value) || $data[$this->codename] === '') {
			return sprintf('Es requerido', $this->name);
		}
	}

	public function match($val) {
		$this->v['match'] = $val;
		return $this;
	}

	public function matchCheck(array $data) {
		$val = $this->value;
		if($val != '' && !preg_match($this->v['match'], $val)) {
			return 'No es válido';
		}
	}

	/**
	 * Use function to validate the value.
	 *
	 * The function will be passed the value being validated as only param. The
	 * field will be valid if and only if the call to function returns true. If
	 * the return value is a string, it will be used as message; otherwise a
	 * a generic message will be emitted.
	 */
	public function with($function) {
		if(!function_exists($function)) {
			throw new InvalidArgumentException('The function does not exist');
		}
		$this->v['with'] = $function;
		return $this;
	}

	public function withCheck(array $data) {
		$val = $this->value;
		if($val != '') {
			$fun = $this->v['with'];
			$result = $fun($val);
			if($result !== true) {
				if(is_string($result)) {
					return $result;
				}
				return 'El valor no es válido';
			}
		}
	}

	public function baseCheck() {
		return array();
	}

	/**
	 * Run the validation set.
	 */
	public function check(array $data) {
		$this->value = $this->arrGet($this->codename, $data);
		$this->isEmpty = is_null($this->value) || strlen($this->value) > 0;
		$errors = $this->baseCheck();
		foreach($this->v as $k => $v) {
			$method = $k."Check";
			if($msg = $this->$method($data)) {
				$errors[] = $msg;
			}
		}
		if(count($errors) > 0) {
			$e = new ValidationError("El valor es inválido");
			$e->setErrors($errors);
			throw $e;
		}
	}

	public function arrGet($key, array $arr, $default=null) {
		$ret = $default;
		if(array_key_exists($key, $arr)) {
			return $arr[$key];
		}
		return $default;
	}
}

?>
