<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $region_num
 * @property string $type
 *
 * @property City[] $cities
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'region_num'], 'required'],
            [['region_num'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 100]
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
            'region_num' => 'Region Num',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['region_id' => 'id'])->orderBy(['name' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     * @return RegionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegionQuery(get_called_class());
    }
}
