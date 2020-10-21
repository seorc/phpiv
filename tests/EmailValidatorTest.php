<?php

use Phpiv\EmailValidator;
use Phpiv\ValidationError;
use PHPUnit\Framework\TestCase;

class EmailValidatorTest extends TestCase {

	public function testChainableMethosAreDoingWell() {
		$v = new EmailValidator('email');
		$this->assertSame($v, $v->required());
	}


	public function emailsProvidaer() {
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

	/**
	 * @dataProvider emailsProvidaer
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
