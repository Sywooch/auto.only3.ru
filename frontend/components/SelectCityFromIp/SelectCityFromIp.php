<?php

namespace frontend\components\SelectCityFromIp;

use yii;
use yii\base\Behavior;
use yii\web\Controller;

use frontend\models\City;

class SelectCityFromIp extends Behavior {
    public $custom_info = NULL;

    public function events() {
        return [ Controller::EVENT_BEFORE_ACTION => 'getCityFromIp', ];
    }

    public function getCityFromIp(){

        $controller = $this->owner;
        $session = Yii::$app->session;
        $city = '';

        if (!$session->isActive) {
            $session->open();
        }

        $cookCityUrl = Yii::$app->request->cookies->getValue('city_url');

        if(Yii::$app->request->get('city_url')){

            $city = City::findOne(['trans' => Yii::$app->request->get('city_url')]);

        } elseif($cookCityUrl) {

            $city = City::findOne(['trans' => $cookCityUrl]);

        } else {

                $geo = new Geo();
                $data = $geo->get_value();

                if (!empty($data)) {//если не определился город подставляем москву
                    $city = City::findOne(['name' => $data['city']]);
                }

        }

        if(empty($city)){
            $city = City::findOne(['name' => 'Москва']);
        }

        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'city_url',
            'value' => $city->trans
        ]));
/*
        $session->set('xy', $city->xy);
        $session->set('city', $city->name);
        $session->set('city_trans', $city->trans);
*/
        $controller->xy = $city->xy;
        $controller->city = $city->name;
        $controller->city_url = $city->trans;
        $controller->city_padezh = $city->padezh;

        Yii::$app->params['city_url'] = $city->trans;

    }

}