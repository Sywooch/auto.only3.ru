<?php

namespace frontend\components;

use PhpOffice\PhpWord\Writer\Word2007;
use Yii;

class Word2007Only extends Word2007 {

    protected function getTempFile($filename)
    {

        $tmpDir = Yii::getAlias('@runtime');

        // Temporary directory
        $this->setTempDir($tmpDir . '/PHPWordWriter/');

        // Temporary file
        $this->originalFilename = $filename;
        if (strtolower($filename) == 'php://output' || strtolower($filename) == 'php://stdout') {
            $filename = tempnam($tmpDir, 'PhpWord');
            if (false === $filename) {
                $filename = $this->originalFilename;
            }
        }
        $this->tempFilename = $filename;

        return $this->tempFilename;
    }

}

