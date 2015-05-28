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
}

?>
