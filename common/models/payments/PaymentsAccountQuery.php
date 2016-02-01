<?php

namespace common\models\payments;

/**
 * This is the ActiveQuery class for [[PaymentsAccount]].
 *
 * @see PaymentsAccount
 */
class PaymentsAccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PaymentsAccount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PaymentsAccount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}