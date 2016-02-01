<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Only3Controller extends Controller
{
    public $layout = "main";
    public $city = "Москва";
    public $xy = "55.748718,37.627428";
    public $title;
    public $city_url;
    public $city_padezh;

    public function setNotFoundHttpException($textError = 'Извините, запрошенная страница не найдена'){
        throw new NotFoundHttpException($textError);
    }

    public function setForbiddenHttpException($textError = 'Извините, у Вас не достаточно прав для осуществления данной операции'){
        throw new ForbiddenHttpException($textError);
    }

    public function init()
    {
        $this->on('beforeAction', function ($event) {
            // запоминаем страницу неавторизованного пользователя, чтобы потом отредиректить его обратно с помощью  goBack()
            if (Yii::$app->getUser()->isGuest) {
                $request = Yii::$app->getRequest();
                // исключаем страницу авторизации или ajax-запросы
                if (!($request->getIsAjax() || strpos($request->getUrl(), 'auth') !== false)) {
                    Yii::$app->getUser()->setReturnUrl($request->getUrl());
                }
            }
        });

        return parent::init();
    }
}