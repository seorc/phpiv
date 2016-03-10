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
}
