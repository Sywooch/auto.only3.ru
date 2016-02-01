<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auto_brands".
 *
 * @property integer $id
 * @property string $name
 *
 * @property AutoModels[] $autoModels
 * @property AutoModelsCopy[] $autoModelsCopies
 */
class AutoBrands extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_brands';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoModels()
    {
        return $this->hasMany(AutoModels::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoModelsCopies()
    {
        return $this->hasMany(AutoModelsCopy::className(), ['brand_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AutoBrandsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AutoBrandsQuery(get_called_class());
    }
}
