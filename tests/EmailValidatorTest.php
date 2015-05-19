<?php

class EmailValidatorTest extends PHPUnit_Framework_TestCase {

	public function testChainableMethosAreDoingWell() {
		$v = new EmailValidator('email');
	}


	public function invalidEmailProvider() {
		return array(
			// Invalid inputs.
			array('uno', false),
			array('mailwrong.com', false),
			array('bad@bad', false),
			array('thisisnot at domain dot com', false),
			// Valid inputs.
			array('example@example.com', true),
			array('test@domain.com.mx', true),
			array('test.withdot@domain.com', true),
			array('test-withdash@domain-mo.com', true),
		);
	}

	public function validEmailProvider() {
		return array(
		);
	}

	/**
	 * @dataProvider invalidEmailProvider
	 */
	public function testInvalidIsDetected($email, $valid) {
		$v = new EmailValidator('email');	
		try{
			$v->check(array('email' => $email));
			$errors = array();
		}
		catch(ValidationError $e) {
			$errors = $e->getErrors();
		}
		if($valid) {
			$this->assertCount(0, $errors);
		}
		else {
			$this->assertCount(1, $errors);
		}
	}
}
