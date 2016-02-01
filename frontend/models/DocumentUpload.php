<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 02.12.2015
 */

namespace frontend\models;

use frontend\modules\profile\models\confirm\Contract;
use Yii;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class DocumentUpload extends Model
{

    public $file;
    public $userId;
    public $uploadDirectory;

    public function init(){
        $this->uploadDirectory = Contract::DIR_UPLOAD;
        $this->userId = Yii::$app->user->id;

        return parent::init();
    }

    public function rules()
    {
        return [
            ['file','file','skipOnEmpty' => false, 'extensions'=> ['odt','docx']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Загрузить новый шаблон договора',
        ];
    }

    public function upload()
    {
        $directory = $this->uploadDirectory . $this->userId.'/';
        $directory = Yii::getAlias($directory);

        FileHelper::createDirectory($directory);

        $file = $this->file;

        if ($this->validate()) {
            //$fPath = $directory . md5($file->size.$file->baseName) . '.' . $file->extension;
            $fPath = $directory . $file->name;

            if(is_file($fPath)) {
                unlink($fPath);
            }
            $this->file->saveAs($fPath);
            return $fPath;

        } else {
            return false;
        }

    }

}