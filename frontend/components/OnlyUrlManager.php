<?php
/**
 * Created by PhpStorm.
 * User: farm
 * Date: 03.09.2015
 * Time: 20:23
 */

namespace frontend\components;

use yii\web\UrlManager;
use Yii;
use yii\helpers\ArrayHelper;

class OnlyUrlManager extends UrlManager
{

    /*
    public function createUrl($params)
    {

        $city_url = ArrayHelper::getValue(Yii::$app->params, 'city_url');
        if($city_url)
            $params['city_url'] = $city_url;

        return parent::createUrl($params);

    }
    */

}