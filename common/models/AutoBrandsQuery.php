<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[AutoBrands]].
 *
 * @see AutoBrands
 */
class AutoBrandsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

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