<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 17.11.2015
 * Time: 13:31
 */

namespace frontend\modules\account\models;


use Yii;

class AccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
{
    $this->andWhere('[[status]]=1');
    return $this;
}*/

    /**
     * @inheritdoc
     * @return Rentact[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Rentact|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function salons($db = null)
    {
        $this->andWhere(['is_salon' => '1']);
        return $this;
    }


}