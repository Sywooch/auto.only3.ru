<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 27.11.2015
 * Time: 2:00
 */

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[AutoBrands]].
 *
 * @see AutoBrands
 */
class UsersDataQuery extends \yii\db\ActiveQuery
{

    public function forSalon($id = false)
    {
        if($id) {
            $this->andWhere(['id' => $id]);
        }
        $this->andWhere(['salon_account_id' => \Yii::$app->user->id]);
        $this->andWhere(['deleted' => '0']);
        $this->andWhere(['not', ['full_name' => '']]);

        return $this;
    }

    public function inBlack($id = false){
        $this->andWhere(['is_black' => '1']);
        $this->andWhere(['deleted' => '0']);
        //$this->andFilterWhere(['or', 'license_number =: license_number', ['and', 'passport_serion =: passport_serion', 'passport_number =: passport_number']]);
        //$this->andFilterWhere(['or', 'license_number =: license_number', ['and', 'passport_serion =: passport_serion', 'passport_number =: passport_number']]);

        //$this->orFilterWhere(['or', 'license_number', $this->license_number]);
        return $this;
    }

    public function inBlackNotModerated($id = false){

        $this->andWhere(['salon_account_id' => \Yii::$app->user->id]);
        $this->andWhere(['deleted' => '0']);
        $this->andWhere(['not', ['full_name' => '']]);
        $this->andWhere(['is_black' => '1']);
        //$this->andFilterWhere(['or', 'license_number =: license_number', ['and', 'passport_serion =: passport_serion', 'passport_number =: passport_number']]);
        //$this->andFilterWhere(['or', 'license_number =: license_number', ['and', 'passport_serion =: passport_serion', 'passport_number =: passport_number']]);

        //$this->orFilterWhere(['or', 'license_number', $this->license_number]);
        return $this;
    }


    /**
     * @inheritdoc
     * @return AutoBrands[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AutoBrands|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}