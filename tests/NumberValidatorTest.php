<?php

use Phpiv\NumberValidator;
use Phpiv\ValidationError;
use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase {

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
			$this->expectException('Phpiv\ValidationError');
		}
		$v->check(array('numval' => $input));
		$this->addToAssertionCount(1);
	}

	/**
	 * @dataProvider valuesProvider
	 */
	public function testMaxIsWorking($input) {
		$m = 8;
		$v = new NumberValidator('numval');
		$v->max($m);
		if($input > $m) {
			$this->expectException('Phpiv\ValidationError');
		}
		$v->check(array('numval' => $input));
		$this->addToAssertionCount(1);
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
			$this->expectException('InvalidArgumentException');
		}
		$v->max($min);
		$this->addToAssertionCount(1);
	}

	/**
	 * @dataProvider invalidMinMaxProvider
	 */
	public function testExceptionOnWrongMax($max, $valid) {
		$v = new NumberValidator('val');
		if(!$valid) {
			$this->expectException('InvalidArgumentException');
		}
		$v->max($max);
		$this->addToAssertionCount(1);
	}

	public function testMaxCannotBeLessThanMin() {
		$this->expectException(\InvalidArgumentException::class);
		$v = new NumberValidator('val');
		$v->min(4)->max(3);
	}

	public function testMinCannotBeGreaterThanMax() {
		$this->expectException(\InvalidArgumentException::class);
		$v = new NumberValidator('val');
		$v->max(4)->min(5);
	}

}

?>
