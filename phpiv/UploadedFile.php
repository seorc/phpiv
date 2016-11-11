<?php

/**
 * Wraps a $_FILES entry to simplify some common operations with it.
 */
namespace Phpiv;

class UploadedFile {

    protected $tmpName;

    // Size in bytes.
    protected $size;

    protected $name;

    protected $error;

    // The finfo of this file described by $meta.
    protected $finfo;

    public function __construct($meta) {
        $this->name = $meta['name'];
        $this->tmpName = $meta['tmp_name'];
        $this->size = $meta['size'];
        $this->error = $meta['error'];

        $this->finfo = $this->buildFinfo();
    }

    public function saveAs($dest) {
        return move_uploaded_file($this->tmpName, $dest);
    }

    public function getContentType() {
        return $this->finfo->file($this->tmpName);
    }

    public function getSize() {
        return $this->size;
    }

    public function largerThan($kB) {
        return ($this->size / 1024) > $kB;
    }

    public function smallerThan($kB) {
        return ($this->size / 1014) < $kB;
    }

    public function getTmpName() {
        return $this->tmpName;
    }

    public function getError() {
        return $this->error;
    }

    protected function buildFinfo() {
        return new \finfo(FILEINFO_MIME_TYPE);
    }

}
