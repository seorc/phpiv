<?php

namespace Phpiv;

use InvalidArgumentException;

/**
 * Work on an array of data according to PHP's $_FILE superglobal, it is to say
 * an array containing an entry for each file uploaded. Each entry contains in
 * turn a nested array with the properties of the file it represents.
 *
 * @see http://php.net/manual/en/features.file-upload.post-method.php
 */
class FileValidator extends Validator {

    /**
     * Specify a maximum size for the file in kB.
     */
    public function max($kB) {
        if(!is_int($kB)) {
            throw new InvalidArgumentException(
                'You must pass an integer to this method');
        }
        $this->v['max'] = $kB;
        return $this;
    }

    protected function maxCheck(array $data) {
        $max = $this->v['max'];
        if(!$this->isEmpty && $this->value['size'] / 1024 > $max) {
            return "El archivo no debe pesar más de $max kB";
        }
    }

    /**
     * Which content types must be validated.
     */
    public function type(array $types) {

        return $this;
    }

}
