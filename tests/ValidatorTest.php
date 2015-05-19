<?php

class ValidatorTest extends PHPUnit_Framework_TestCase {

	public function testChainiableMethodsReturnThis() {
		$v = new Validator('testval');
		function afunc($val) {}
		$this->assertEquals($v->required(), $v);
		$this->assertEquals($v->match('/.*/'), $v);
		$this->assertEquals($v->with('afunc'), $v);
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

}

?>
