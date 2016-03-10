<?php

class StringValidatorTest extends PHPUnit_Framework_TestCase {

    public function fixedLenghtProvider() {
		return [
			['aaaa', true],
			['bbb', false],
			['ccccc', false],
		];
	}

    /**
     * @dataProvider fixedLenghtProvider
     */
    public function testLengtSingleParamMeansFixedLenght($value, $valid) {
        $v = new StringValidator('tested');
        $v->length(4);
        if(!$valid) $this->setExpectedException('ValidationError');
        $v->check(['tested' => $value]);
    }


    public function rangedLengthProvider() {
        return [
            ['1234', true],
            ['123', false],
            ['123456', true],
            ['1234567', false],
        ];
    }

    /**
     * @dataProvider rangedLengthProvider
     */
    public function testLengthCanValidateRanges($value, $valid) {
        $v = new StringValidator('tested');
        $v->length(4, 6);
        if(!$valid) $this->setExpectedException('ValidationError');
        $v->check(['tested' => $value]);
    }
}
