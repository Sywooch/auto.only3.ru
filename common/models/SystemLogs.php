<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "system_logs".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $userIP
 * @property string $userHost
 * @property string $UserAgent
 * @property string $Url
 * @property string $Get
 * @property string $Post
 * @property string $Headers
 */
class SystemLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_at'], 'safe'],
            [['userIP', 'userHost', 'UserAgent', 'Url', 'Get'], 'string', 'max' => 255],
            [['Post'], 'string', 'max' => 1000],
            [['Headers'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'userIP' => 'User Ip',
            'userHost' => 'User Host',
            'UserAgent' => 'User Agent',
            'Url' => 'Url',
            'Get' => 'Get',
            'Post' => 'Post',
            'Headers' => 'Headers',
        ];
    }

    public static function WriteAccessLog($custom = ''){

        $model = new self();

        $model->userHost = Yii::$app->request->userHost;
        $model->userIP = Yii::$app->request->userIP;
        $model->UserAgent = Yii::$app->request->userAgent;
        $model->Url = Yii::$app->request->url;
        $model->Get = serialize(Yii::$app->request->get());
        $model->Post = serialize(Yii::$app->request->post());

        $model->created_at =  new \yii\db\Expression('NOW()');

        $model->custom =  $custom;

        $model->save();
    }

}
