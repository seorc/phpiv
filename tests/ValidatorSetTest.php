<?php

use Phpiv\ValidatorSet;
use Phpiv\EmailValidator;
use Phpiv\NumberValidator;

class ValidatorSetTest extends PHPUnit_Framework_TestCase {

	public function validatorClassNamesProvider() {
		return array(
			// Valid names.
			array('string', true),
			array('date', true),
			array('basic', true),
			array('Phpiv\EmailValidator', true),
			array('Phpiv\NumberValidator', true),
			// Invalid names.
			array('w', false),
			array('dates', false),
			array('DateTime', false),
		);
	}

	/**
	 * @dataProvider validatorClassNamesProvider
	 */
	public function testInvalidValidatorTypeIsDetected($name, $validName) {
		if(!$validName) {
			$this->setExpectedException('InvalidArgumentException');
		};
		$vs = new ValidatorSet();
		$vs->add($name, 'inputname');
	}

	public function testGetCleanedReturnsKeyValueData() {
		$data = array(
			'num' => 1,
			'dat' => '2090-10-10',
			'str' => 'Hello!',
			'ignored' => 2.00001,
		);

		$vs = new ValidatorSet();
		$vs->add('number', 'num')->required();
		$vs->add('date', 'dat')->format(array('Y-m-d'));
		$vs->add('string', 'str')->minLength(2);
		$vs->add('string', 'unexistent');
		$vs->add('number', 'unexistent2');

		$vs->check($data);
		$cleaned = $vs->getCleaned();

		$this->assertCount(5, $cleaned);
		$this->assertThat($cleaned, $this->arrayHasKey('num'));
		$this->assertThat($cleaned,
		   	$this->logicalNot($this->arrayHasKey('ignored')));
	}
}

?>
