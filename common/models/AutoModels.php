<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auto_models".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $name
 *
 * @property AutoBrands $brand
 */
class AutoModels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_models';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id'], 'integer'],
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
            'brand_id' => 'Brand ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(AutoBrands::className(), ['id' => 'brand_id']);
    }

    /**
     * @inheritdoc
     * @return AutoModelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AutoModelsQuery(get_called_class());
    }
}
