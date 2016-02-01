<?php

namespace frontend\modules\account;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

use frontend\modules\account\models\Account;

class Module extends \yii\base\Module
{
    public $enableCaptcha = false;

    public $phoneAttribute = 'phone';

    public $layout = '@app/views/layouts/main';

    public $controllerNamespace = 'frontend\modules\account\controllers';

    const I18N_PREFIX = 'nord/account/';
    const CLASS_ACCOUNT = 'frontend\modules\account\models\Account';

    const PARAM_FROM_EMAIL_ADDRESS = 'auto@only3.ru';
    const PARAM_MIN_USERNAME_LENGTH = 4;
    const PARAM_MIN_PASSWORD_LENGTH = 6;

    const URL_ROUTE_CAPTCHA = '/account/auth/captcha';
    const URL_ROUTE_FORGOT_PASSWORD = '/account/auth/request-password-reset';
    const URL_ROUTE_LOGIN = '/account/auth/login';
    const URL_ROUTE_SIGNUP = '/account/auth/signup';
    const URL_ROUTE_LOGOUT = '/account/auth/logout';
    const URL_ROUTE_PROFILE = '/profile/system/index';
    const URL_AFTER_REGISTRATION = '/profile/users-data/create-to-black';
    const URL_ROUTE_CHANGE_PASSWORD = '/account/auth/change-password';


    const PARAM_LOGIN_EXPIRE_TIME = 2592000; //month

    public $classMap = [];

    protected function initParams()
    {
        $this->params = ArrayHelper::merge(
            [
                self::PARAM_FROM_EMAIL_ADDRESS => 'admin@example.com',
                self::PARAM_MIN_USERNAME_LENGTH => 4,
                self::PARAM_MIN_PASSWORD_LENGTH => 6,
                self::PARAM_LOGIN_EXPIRE_TIME => 2592000, // 30 days
            ],
            $this->params
        );
    }

    public function init()
    {
        parent::init();

        $this->initClassMap();
        $this->initParams();

        // custom initialization code goes here
    }

    protected function initClassMap()
    {
        $this->classMap = ArrayHelper::merge(
            [
                self::CLASS_ACCOUNT => Account::className(),
            ],
            $this->classMap
        );
    }


    public static function t($category, $message, array $params = [])
    {
        return Yii::t(self::I18N_PREFIX . $category, $message, $params);
    }

    public function getClassName($type)
    {
        if (!isset($this->classMap[$type])) {
            throw new InvalidParamException("Trying to get class name for unknown class '$type'.");
        }
        return $this->classMap[$type];
    }

    public static function getParam($name)
    {
        $params = self::getInstance()->params;
        if (!isset($params[$name])) {
            throw new InvalidParamException("Trying to get unknown parameter '$name'.");
        }
        return $params[$name];
    }

    public function createRoute($route)
    {
        return '/' . $this->urlConfig['routePrefix'] . '/' . $route;
    }

}
