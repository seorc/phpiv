<?php

class Validator {

	protected $v;
	protected $apply;
	public $name;
	public $codename;
	public $isNull; // ture when the field is null;
	public $isBlank; // true when the field is blank;
	public $isEmpty; // Implies null or blank.
	public $value;
	public $cleanedValue = null;

	public function __construct($codename, $name='') {
		$this->name = $name ? $name : $codename;
		$this->codename = $codename;
		$this->v = array();
		$this->apply = array();
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
		$this->isNull = is_null($this->value);
		$this->isBlank = !$this->isNull && strlen($this->value) === 0;
		$this->isEmpty = $this->isNull || $this->isBlank;
		$this->cleanedValue = $this->clean();
		$this->applyApply();
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

	protected function clean() {
		return $this->value;
	}

	/**
	 * Applay a function on the input.
	 *
	 * You can apply many functions to the validator and they will be executed 
	 * in the order you pass them to it. This method is called once the 
	 * validator has cleanedValue set.
	 *
	 * This method is chainable.
	 *
	 * @param string $function The function name to call. This function's 
	 * return value will overrwrite the value of $this->cleanedValue.
	 * @param array $args The arguments to call the function with. The special
	 * ':input' argument can be included in this array to tell this validator
	 * where to position the input it is validatin in the function call.
	 * Otherwise it will pass the input value as the first argument to the
	 * function.
	 */
	public function apply($function, array $args=null) {
		if(!function_exists($function)) {
			throw new InvalidArgumentException('You must pass an existing function name');
		}
		if(is_null($args)) {
			$args = array();
		}
		$this->apply[] = array(
			'f' => $function,
			'args' => $args,
		);
		return $this;
	}

	/**
	 * Call the set of functions to be applied on $this->cleanedValue.
	 */
	protected function applyApply() {
		foreach($this->apply as $a) {
			$args = $a['args'];
			$input_pos = array_search(':input', $args);
			if($input_pos === false) {
				array_unshift($args, $this->cleanedValue);
			}
			else {
				$argat = array($input_pos => $this->cleanedValue);
				$args = array_replace($args, $argat);
			}

			$this->cleanedValue = call_user_func_array($a['f'], $args);
		}
	}

}

?>
