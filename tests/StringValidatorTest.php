<?php

use Phpiv\StringValidator;
use Phpiv\ValidationError;

class StringValidatorTest extends PHPUnit_Framework_TestCase {

    public function fixedLenghtProvider() {
		return array(
			array('aaaa', true),
			array('bbb', false),
			array('ccccc', false),
		);
	}

    /**
     * @dataProvider fixedLenghtProvider
     */
    public function testLengtSingleParamMeansFixedLenght($value, $valid) {
        $v = new StringValidator('tested');
        $v->length(4);
        if(!$valid) $this->setExpectedException('Phpiv\ValidationError');
        $v->check(array('tested' => $value));
    }


    public function rangedLengthProvider() {
        return array(
            array('1234', true),
            array('123', false),
            array('123456', true),
            array('1234567', false),
        );
    }

    /**
     * @dataProvider rangedLengthProvider
     */
    public function testLengthCanValidateRanges($value, $valid) {
        $v = new StringValidator('tested');
        $v->length(4, 6);
        if(!$valid) $this->setExpectedException('Phpiv\ValidationError');
        $v->check(array('tested' => $value));
    }
}
