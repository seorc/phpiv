<?php

use Phpiv\FileValidator;
use Phpiv\UploadedFile;
use Phpiv\ValidationError;
use PHPUnit\Framework\TestCase;

class FileValidatorTest extends TestCase {


    protected $files;

    protected function setUp(): void {
        $this->files = array(
            'photo' => array(
                'name' => 'myphoto.jpg',
                'type' => 'image/jpg',
                'size' => 2048,
                'tmp_name' => '/tmp/file',
                'error' => UPLOAD_ERR_OK,
            ),
            'nofphoto' => array(
                'name' => 'myphoto.jpg',
                'type' => 'image/jpg',
                'size' => 2048,
                'tmp_name' => '/tmp/file',
                'error' => UPLOAD_ERR_NO_FILE,
            ),
        );
    }

    public function testMaxIsValidated() {
        $v = new FileValidator('photo');

        $v->max(2);
        $v->check($this->files);

        $this->expectException('Phpiv\ValidationError');
        $v->max(1);
        $v->check($this->files);

    }

    public function testContentTypeIsChecked() {
        $ufMock = $this->getMockBuilder('Phpiv\UploadedFile')
            ->disableOriginalConstructor()
            ->setMethods(['getContentType'])
            ->getMock();

        $ufMock->expects($this->exactly(2))
            ->method('getContentType')
            ->will($this->onConsecutiveCalls('image/gif', 'image/jpg'));

        $v = $this->getMockBuilder('Phpiv\FileValidator')
            ->setConstructorArgs(['photo'])
            ->setMethods(['clean'])
            ->getMock();
        $v->method('clean')->willReturn($ufMock);
        $v->contentType(array(
            'gif' => 'image/gif',
            'png' => 'image/png',
        ));

        // Will not throw exception.
        $v->check($this->files);

        // Wil throw.
        $this->expectException('Phpiv\ValidationError');
        $v->check($this->files);
    }

    public function uploadErrorProvider() {
        return array(
            array(UPLOAD_ERR_OK, false), // No error.
            array(UPLOAD_ERR_NO_FILE, true),
            array(UPLOAD_ERR_INI_SIZE, true),
            array(UPLOAD_ERR_FORM_SIZE, true),
            array(2587845, true),
        );
    }

    /**
     * @dataProvider uploadErrorProvider
     */
    public function testBaseCheckIsPerformed($errCode, $mustFail) {
        if($mustFail) {
            $this->expectException('Phpiv\ValidationError');
        }

        $v = new FileValidator('photo');
        $this->files['photo']['error'] = $errCode;
        $v->check($this->files);
        $this->addToAssertionCount(1);
    }
}
