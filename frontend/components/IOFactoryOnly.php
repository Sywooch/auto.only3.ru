<?php

namespace frontend\components;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class IOFactoryOnly extends IOFactory{

    public static function createWriter(PhpWord $phpWord, $name = 'Word2007')
    {
        if ($name !== 'Word2007Only' && !in_array($name, array('ODText', 'RTF', 'Word2007', 'HTML', 'PDF'), true)) {
            throw new Exception("\"{$name}\" is not a valid writer.");
        }

        $fqName = "PhpOffice\\PhpWord\\Writer\\{$name}";
        if($name == 'Word2007Only'){
            $fqName = "frontend\\components\Word2007Only";
        }

       return new $fqName($phpWord);
    }

}