<?php

namespace frontend\models;

use Yii;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class UploadUserDataImage extends Model
{

    public $imageFile;
    public $fileName;
    public $userDataId;

    public $directory = 'protected/images/userdata/';
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function relocate($file, $from, $toDir){

        $webRoot = \Yii::getAlias('@webroot'). DIRECTORY_SEPARATOR;

        $toDir = $webRoot. $toDir;

        FileHelper::createDirectory($toDir);
        $fullFilePath = $webRoot . $from. $file;

        if(!file_exists( $fullFilePath)){
            $error = 'Файл нет существует: ' . $fullFilePath;
            return $error;
        }

        # Копируем файл
        if( copy( $fullFilePath, $toDir . $file ) )
        {
            unlink( $fullFilePath );
            return true;
        }

        return false;

    }

    public function upload()
    {

        $directory = $this->directory . $this->userDataId;
        FileHelper::createDirectory($directory);
        $file = $this->imageFile;

        if ($this->validate()) {
            $this->fileName = md5(time().rand(0,10000000).$file->size.$file->baseName) . '.' . $file->extension;
            $fPath = $directory . '/' . $this->fileName;
            $this->imageFile->saveAs($fPath);

            return $fPath;

        } else {
            return false;
        }
    }

}