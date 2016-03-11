<?php

use Phpiv\BooleanValidator;
use Phpiv\ValidationError;

class BooleanValidatorTest extends PHPUnit_Framework_TestCase {

	public function notBooleansProvider() {
		return array(
			array('true'),
			array('false'),
			array(1),
			array(0),
			array('sojf'),
			array('bad'),
		);
	}

	/**
	 * @dataProvider notBooleansProvider
	 */
	public function testErrorOnNotBooleanInput($notbool) {
		$v = new BooleanValidator('boolvalue');
		$this->setExpectedException('Phpiv\ValidationError');
		$v->check(array('boolvalue' => $notbool));
	}

	public function testBooleanOrBoolIsReturned() {
		$v = new BooleanValidator('boolvalue');
		$v->check(array());
		$this->assertNull($v->cleanedValue);
		$v->check(array('boolvalue' => true));
		$this->assertTrue($v->cleanedValue);
		$v->check(array('boolvalue' => false));
		$this->assertFalse($v->cleanedValue);
	}
}

?>
