<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property string $name
 * @property integer $region_id
 * @property string $xy
 * @property string $regname
 * @property string $trans
 * @property string $padezh
 *
 * @property Region $region
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'region_id', 'xy'], 'required'],
            [['region_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['xy'], 'string', 'max' => 100],
            [['regname', 'trans', 'padezh'], 'string', 'max' => 200]
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
            'region_id' => 'Region ID',
            'xy' => 'Xy',
            'regname' => 'Regname',
            'trans' => 'Trans',
            'padezh' => 'Padezh',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    /**
     * @inheritdoc
     * @return CityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CityQuery(get_called_class());
    }

    public static function getCityList(){

        $citys = City::find()->all();

        $citys = ArrayHelper::map($citys,'id','name');

        return $citys;
    }
}
