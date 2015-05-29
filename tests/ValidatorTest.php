<?php

class ValidatorTest extends PHPUnit_Framework_TestCase {

	public function testChainiableMethodsReturnThis() {
		$v = new Validator('testval');
		function afunc($val) {}
		$this->assertEquals($v->required(), $v);
		$this->assertEquals($v->match('/.*/'), $v);
		$this->assertEquals($v->with('afunc'), $v);
		$this->assertEquals($v->apply('afunc', array(':input')), $v);
		$this->assertEquals($v->defaultInput(3), $v);
	}

	public function testIsEmptyWhenNullOrBlank() {
		$v = new Validator('testval');
		$v->check(array());
		$this->assertTrue($v->isEmpty);
		$v->check(array('testval' => ''));
		$this->assertTrue($v->isEmpty);
	}

	public function testIsNull() {
		$v = new Validator('testval');
		$v->check(array());
		$this->assertTrue($v->isNull);
		$v->check(array('testval' => ''));
		$this->assertFalse($v->isNull);
	}

	public function testIsBlank() {
		$v = new Validator('testval');
		$v->check(array());
		$this->assertFalse($v->isBlank);
		$v->check(array('testval' => ''));
		$this->assertTrue($v->isBlank);
	}

	public function testApplyCallIsWorking() {
		$v = new Validator('afield');
		$v->apply('mb_strtoupper', array(':input', 'UTF-8'));
		$v->check(array('afield' => 'ifñoass9áááñslfjm+´âà~aáäA'));
		$this->assertEquals('IFÑOASS9ÁÁÁÑSLFJM+´ÂÀ~AÁÄA', $v->cleanedValue);
	}

	public function testMultipleApplyIsWorking() {
		$v = new Validator('afield');
		$v->apply('mb_strtoupper', array(':input', 'UTF-8'));
		$v->apply('rtrim');
		$v->check(array('afield' => ' josijf oooO  '));
		$this->assertEquals(' JOSIJF OOOO', $v->cleanedValue);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testApplayThrowsExceptionOnUnexistentFunction() {
		$v = new Validator('afield');
		$v->apply('thisfunctshouldnotexistttt', array());
	}

	public function testDefaultInputIsBeingUsed() {
		$v = new Validator('in');
		$v->defaultInput(6);
		$v->check(array('in' => 10));
		$this->assertEquals(10, $v->cleanedValue);
		$v->check(array());
		$this->assertEquals(6, $v->cleanedValue);
	}

	/**
	 *  @expectedException ValidationError
	 */
	public function testRequiredDetectsUniexistingKeyOnInput() {
		$v = new Validator('in');
		$v->required();
		$v->check(array());
	}

	/**
	 * Bug exposing test.
	 *
	 * Exposes a bug when defaultInput() and required() are used together:
	 * Validator tried to access an unexisting index in input data when both
	 * filters where used, preventing this validator to perform a complete 
	 * validation.
	 *
	 * @expectedException ValidationError
	 */
	public function testRequiredDoesNotColideWithDefaultInput() {
		$v = new Validator('in');
		$v->defaultInput(0)->required();
		$v->check(array());
	}

	public function testRequiredApplyEvenIfDefaultInputIsSet() {
		$v = new Validator('in');
		$v->defaultInput(0);
		$v->check(array());
		$this->assertEquals(0, $v->cleanedValue);

		$this->setExpectedException('ValidationError');
		$v->required();
		$v->check(array());
	}
}

?>
