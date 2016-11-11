<?php

namespace Phpiv;

use InvalidArgumentException;

/**
 * Work on an array of data structured according to PHP's $_FILES superglobal,
 * it is to say an array containing an entry for each file uploaded. Each entry
 * contains in turn a nested array with the properties of the file it
 * represents. This validator works on a single entry of $_FILES.
 *
 * @see http://php.net/manual/en/features.file-upload.post-method.php
 */
class FileValidator extends Validator {

    public function clean() {
        return new UploadedFile($this->value);
    }

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
        if(!$this->isEmpty && $this->cleanedValue->largerThan($max)) {
            return "El archivo no debe pesar más de $max kB";
        }
    }

    /**
     * Which content types must be validated.
     *
     * This validator appends the contentType and the
     */
    public function contentType(array $types) {
        $this->v['contentType'] = $types;
        return $this;
    }

    protected function contentTypeCheck(array $data) {
        if(!$this->isEmpty) {
            $ext = array_search(
                $this->cleanedValue->getContentType(),
                $this->v['contentType'],
                true);
            if(false === $ext) {
                return 'El formato del archivo no es válido';
            }
        }
    }

    public function baseCheck() {
        $errors = parent::baseCheck();
        switch ($this->cleanedValue->getError()) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $errors[] = 'No se envió el archivo';
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = 'El archivo es muy grande';
            default:
                $errors[] = 'Error desconocido';
        }

        return $errors;
    }

}
