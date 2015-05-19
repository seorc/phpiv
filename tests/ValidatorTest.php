<?php

class ValidatorTest extends PHPUnit_Framework_TestCase {

	public function testRequiredReturnsThis() {
		$v = new Validator('testval');
		$this->assertEquals($v->required(), $v);
	}

}

?>
