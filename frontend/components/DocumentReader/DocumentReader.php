<?php
namespace frontend\components\DocumentReader;

use yii\base\Object;

class DocumentReader extends Object
{
    protected $filename;
    protected $replaceWorlds;
    protected $mode;
    protected $contentFile;

    function __construct($filename, $replaceWorlds = []) {
        $this->filename = $filename;
        $this->replaceWorlds = $replaceWorlds;

        $pathInfo = pathinfo($this->filename);
        $this->mode = $pathInfo['extension'];

        if($this->mode === 'odt'){
            $this->contentFile = 'content.xml';
        } else {
            $this->contentFile = 'word/document.xml';
        }
    }

    public function getDocumentText(){

        $pathInfo = pathinfo($this->filename);
        $method = $this->mode.'2text';

        if(method_exists($this, $method)){
            return $this->$method();
        } else {
            throw new \ErrorException('error getDocument '.$this->filename);
        }
    }

    private function odt2text() {
        return $this->getTextFromZippedXML("content.xml");
    }

    private function docx2text() {
        return $this->getTextFromZippedXML("word/document.xml");
    }

    private function getTextFromZippedXML($contentFile) {

        $archiveFile = $this->filename;

        // Создаёт "реинкарнацию" zip-архива...
        $zip = new \ZipArchive;
        // И пытаемся открыть переданный zip-файл
        if ($zip->open($archiveFile)) {
            // В случае успеха ищем в архиве файл с данными
            if (($index = $zip->locateName($contentFile)) !== false) {
                // Если находим, то читаем его в строку
                $content = $zip->getFromIndex($index);
                return $content;
            } else {
                throw new \ErrorException('error read document');
            }
            $zip->close();
        } else {
            throw new \ErrorException('error open file '.$archiveFile);
        }
        return '';
    }

    /**
     * @param array $markers
     * @param string $documentIndex
     * @return mixed
     * @throws \ErrorException
     */
    public function getDocumentFile($markers = [], $documentIndex, $directory){

        $sourceFile = $this->filename;
        $file = basename($sourceFile,'.'.$this->mode);

        $destFile = $directory . $file . '-' . $documentIndex . '.' . $this->mode;
        if(is_file($destFile))
            unlink($destFile);

        if(!is_dir(dirname($destFile)))
            mkdir(dirname($destFile), 0700);

        copy($sourceFile, $destFile);

        $zip = new \ZipArchive;
        // И пытаемся открыть переданный zip-файл
        if ($zip->open($destFile)) {
            $documentXml = $zip->getFromName($this->contentFile);

            $documentXml = str_replace($markers['search'], $markers['replacement'], $documentXml);

            $zip->deleteName($this->contentFile);
            $zip->addFromString($this->contentFile, $documentXml);

            $zip->close();
        }

        return $destFile;
    }

    /**
     * @return mixed|string
     * @throws \ErrorException
     */
    public function convertDocumentToHtml(){

        $content = $this->getDocumentText();

        $xml = new \DOMDocument;
        $contentStr = $xml->loadXML($content, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        $resultHtml = $xml->saveXML();

        if($this->mode == 'odt') {
            $resultHtml = strip_tags($resultHtml, '<text:p>');
            $resultHtml = str_replace('<text:p/>', '', $resultHtml);
            $resultHtml = preg_replace('~<text:p[^>]*>(.*?)</text:p>~ius', '<p>$1</p>', $resultHtml);
        } else {
            $resultHtml = strip_tags($resultHtml, '<w:p>');
            $resultHtml = str_replace('<w:p/>', '', $resultHtml);
            $resultHtml = preg_replace('~<w:p[^>]*>(.*?)</w:p>~ius', '<p>$1</p>', $resultHtml);
        }

        return $resultHtml;
    }
}