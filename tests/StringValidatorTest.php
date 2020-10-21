<?php

use Phpiv\StringValidator;
use Phpiv\ValidationError;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase {

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
        if(!$valid) $this->expectException('Phpiv\ValidationError');
        $v->check(array('tested' => $value));
        $this->addToAssertionCount(1);
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
        if(!$valid) $this->expectException('Phpiv\ValidationError');
        $v->check(array('tested' => $value));
        $this->addToAssertionCount(1);
    }
}
