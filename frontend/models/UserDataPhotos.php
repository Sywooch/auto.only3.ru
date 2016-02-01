<?php

namespace frontend\models;

use Yii;
use frontend\models\Only3Model;
use yii\web\UploadedFile;

use frontend\models\UsersData;
/**
 * This is the model class for table "rentact".
 *
 * @property integer $id
 * @property string $time
 * @property string $days
 * @property string $name
 * @property string $phone
 * @property string $comment
 * @property integer $status
 * @property integer $add
 * @property integer $vacant
 * @property integer $timer
 * @property integer $worker
 * @property string $email
 *
 * @property SystemAuto $add0
 */
class UserDataPhotos extends Only3Model
{

    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'minFiles' => 3, 'maxFiles' => 3],
        ];
    }


    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }

}
