<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "minus".
 *
 * @property string $id
 * @property integer $account
 * @property string $create
 * @property double $size
 */
class Minus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'minus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'create'], 'required'],
            [['account'], 'integer'],
            [['size'], 'number'],
            [['create'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => 'Account',
            'create' => 'Create',
            'size' => 'Size',
        ];
    }
}
