<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[AutoModels]].
 *
 * @see AutoModels
 */
class AutoModelsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AutoModels[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AutoModels|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}