<?php

namespace frontend\modules\account\controllers;

use Yii;
use yii\web\Controller;

use frontend\controllers\Only3Controller;

class AccountController extends Only3Controller
{

//    public $layout = "profile";
    public $title = "Регистрация / авторзация";

    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(),[
            'SelectCityFromIp' => [ 'class' => 'frontend\components\SelectCityFromIp\SelectCityFromIp',],
        ]);
    }

    public function init()
    {
        parent::init();

        $this->layout = $this->module->layout;
    }

}