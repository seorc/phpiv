<?php

namespace Phpiv;

use InvalidArgumentException;

/**
 * Work on an array of data structured according to PHP's $_FILE superglobal,
 * it is to say an array containing an entry for each file uploaded. Each entry
 * contains in turn a nested array with the properties of the file it
 * represents.
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
    public function contentType(array $types) {
        $this->v['contentType'] = $types;
        return $this;
    }

    protected function contentTypeCheck(array $data) {
        if(!$this->isEmpty) {
            $finfo = $this->buildFinfo();
            $ext = array_search(
                $finfo->file($this->value['tmp_name']),
                $this->v['contentType'],
                true);
            if(false === $ext) {
                return 'El formato del archivo no es válido';
            }
        }
    }

    protected function buildFinfo() {
        return new \finfo(FILEINFO_MIME_TYPE);
    }

}
