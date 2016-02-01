<?php

namespace common\models\payments;

/**
 * This is the ActiveQuery class for [[PaymentsTransaction]].
 *
 * @see PaymentsTransaction
 */
class PaymentsTransactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PaymentsTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PaymentsTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return PaymentsTransaction|array|null
     */
    public function asSalon($db = null)
    {
        $this->join('INNER JOIN',
	                'rentact',
	                'payments_transaction.rentact_id =rentact.id'
	            );

        $this->join('INNER JOIN',
            'system_auto',
            'rentact.system_id =system_auto.id'
        );

        $this->andWhere(['system_auto.account_id' => \Yii::$app->user->id]);

        return $this;
    }

}