<?php

class NumberValidatorTest extends PHPUnit_Framework_TestCase {

	public function testChainableMethodsIndeedChain() {
		$v = new NumberValidator('numval');
		$this->assertEquals($v->min(2), $v);
		$this->assertEquals($v->max(3), $v);
		$this->assertEquals($v->between(3, 4), $v);
	}

	public function testValueIsCleanedToNumber() {
		$v = new NumberValidator('numval');
		$v->check(array('numval' => '01'));
		$this->assertThat($v->cleanedValue, $this->isType('int'));
	}

	/**
	 * @dataProvider valuesProvider
	 */
	public function testMinIsWorking($input) {
		$m = 3;
		$v = new NumberValidator('numval');
		$v->min($m);
		if($input < $m) {
			$this->setExpectedException('ValidationError');
		}
		$v->check(array('numval' => $input));
	}

	/**
	 * @dataProvider valuesProvider
	 */
	public function testMaxIsWorking($input) {
		$m = 8;
		$v = new NumberValidator('numval');
		$v->max($m);
		if($input > $m) {
			$this->setExpectedException('ValidationError');
		}
		$v->check(array('numval' => $input));
	}

	public function invalidMinMaxProvider() {
		return array(
			// Invalid inputs.
			array('i9', false),
			array('sofij', false),
			array('true', false),
			array(false, false),
			// Valid inputs.
			array('1', true),
			array(8, true),
			array(8, true),
		);
	}

	public function valuesProvider() {
		return array(
			array(1),
			array(2),
			array(3),
			array(4),
			array(5),
			array(10),
			array(20),
			array(30),
			array(40),
			array(50),
		);
	}

	/**
	 * @dataProvider invalidMinMaxProvider
	 */
	public function testExceptionOnWrongMin($min, $valid) {
		$v = new NumberValidator('val');
		if(!$valid) {
			$this->setExpectedException('InvalidArgumentException');
		}
		$v->max($min);
	}

	/**
	 * @dataProvider invalidMinMaxProvider
	 */
	public function testExceptionOnWrongMax($max, $valid) {
		$v = new NumberValidator('val');
		if(!$valid) {
			$this->setExpectedException('InvalidArgumentException');
		}
		$v->max($max);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testMaxCannotBeLessThanMin() {
		$v = new NumberValidator('val');
		$v->min(4)->max(3);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testMinCannotBeGreaterThanMax() {
		$v = new NumberValidator('val');
		$v->max(4)->min(5);
	}

}

?>
