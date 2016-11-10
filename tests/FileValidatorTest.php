<?php

use Phpiv\FileValidator;
use Phpiv\ValidationError;

class FileValidatorTest extends PHPUnit_Framework_TestCase {


    protected $files;

    protected function setUp() {
        $this->files = array(
            'photo' => array(
                'name' => 'myphoto.jpg',
                'type' => 'image/jpg',
                'size' => 2048,
                'tmp_name' => '/tmp/file',
                'error' => UPLOAD_ERR_OK,
            ),
        );
    }

    public function testMaxIsValidated() {
        $v = new FileValidator('photo');

        $v->max(2);
        $v->check($this->files);

        $this->setExpectedException('Phpiv\ValidationError');
        $v->max(1);
        $v->check($this->files);

    }

    public function testContentTypeIsChecked() {
        $finfoMock = $this->getMockBuilder('\finfo')
            ->setMethods(['file'])
            ->getMock();

        $finfoMock->expects($this->exactly(2))
            ->method('file')
            ->will($this->onConsecutiveCalls('image/gif', 'image/jpg'));

        $v = $this->getMockBuilder('Phpiv\FileValidator')
            ->setConstructorArgs(['photo'])
            ->setMethods(['buildFinfo'])
            ->getMock();
        $v->method('buildFinfo')->willReturn($finfoMock);
        $v->contentType(array(
            'gif' => 'image/gif',
            'png' => 'image/png',
        ));

        // Will not throw exception.
        $v->check($this->files);

        // Wil throw.
        $this->setExpectedException('Phpiv\ValidationError');
        $v->check($this->files);

    }
}
