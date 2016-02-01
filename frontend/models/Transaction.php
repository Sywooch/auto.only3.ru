<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property string $id
 * @property string $time
 * @property integer $user
 * @property integer $price
 * @property string $status
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'user', 'price', 'status'], 'required'],
            [['user', 'price'], 'integer'],
            [['time', 'status'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'user' => 'User',
            'price' => 'Price',
            'status' => 'Status',
        ];
    }
}
