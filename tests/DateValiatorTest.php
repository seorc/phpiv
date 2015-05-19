<?php

class DateValidatorTest extends PHPUnit_Framework_TestCase {

	public function testChainiableMethodsReturnThis() {
		$v = new DateValidator('date');
		$this->assertEquals($v->format(array('dm')), $v);
		$this->assertEquals($v->min('2000-01-01'), $v);
		$this->assertEquals($v->max('2000-01-01'), $v);
		$this->assertEquals($v->between('2000-01-01', '2000-01-02'), $v);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsInvalidOnMinAfterMax() {
		$v = new DateValidator('date');
		$v->min('2000-01-02')->max('2000-01-01');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsInvalidOnMaxBeforeMin() {
		$v = new DateValidator('date');
		$v->max('2000-01-01')->min('2000-01-02');
	}

	public function dateFormatProvider() {
		return array(
			// Invalid inputs.
			array('3112', false),
			array('3101', false),
			array('01101999', false),
			array('---', false),
			// Valid inputs.
			array('31-12', true),
			array('10-02', true),
			array('01/01/2000', true),
			array('10/02/2003', true),
		);
	}

	/**
	 * @dataProvider dateFormatProvider
	 */
	public function testIncorrectFormatIsDetected($date, $valid) {
		$v = new DateValidator('date');
		$v->format(array('d-m', 'd/m/Y'));
		try {
			$v->check(array('date' => $date));
			// The cleaned value must be a DateTime instance.
			$this->assertEquals('DateTime', get_class($v->cleanedValue));
			$this->assertTrue($valid);
		}
		catch(ValidationError $e) {
			$err = $e->getErrors();
			$this->assertCount(1, $err);
			$this->assertEquals('El formato es incorrecto', $err[0]);
			$this->assertFalse($valid);
		}
	}

}

?>
