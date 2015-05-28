<?php

class NumberValidatorTest extends PHPUnit_Framework_TestCase {
	public function testValueIsCleanedToNumber() {
		$v = new NumberValidator('numval');
		$v->check(array('numval' => '01'));
		$this->assertThat($v->cleanedValue, $this->isType('int'));
	}
}

?>
