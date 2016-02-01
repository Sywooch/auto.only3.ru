<?php
/*
 * This file is part of Account.
 *
 * (c) 2014 Nord Software
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace frontend\modules\account\models;

use frontend\modules\account\Module;

use Yii;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class SignupForm extends PasswordForm
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

    public $phone;

    public $city_name;

    public $url;

    /**
     * @var string
     */
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /** @var Module $module */
        $module = Module::getInstance();

        /** @var Account $accountClass */
        $accountClass = $module->getClassName(Module::CLASS_ACCOUNT);

        return ArrayHelper::merge(
            parent::rules(),
            [
                [['email', 'username', 'phone', 'city_name'], 'required'],
                ['username', 'string', 'min' => Module::getParam(Module::PARAM_MIN_USERNAME_LENGTH)],
                ['email', 'email'],
                [['username', 'email'], 'unique', 'targetClass' => $accountClass],
                [['url'], 'url'],
                /*
                [
                    'captcha',
                    'captcha',
                    'captchaAction' => $module->createRoute(Module::URL_ROUTE_CAPTCHA),
                    'on' => 'captcha',
                ],*/

                ['password', 'required'],
                ['password', 'string', 'min' => 6],

            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'phone' => Module::t('labels', 'Телефон'),
                'email' => Module::t('labels', 'E-mail'),
                'url' => Module::t('labels', 'Ссылка на сайт'),
                'city_name' => Module::t('labels', 'Город'),
                'username' => Module::t('labels', 'Автопрокат'),
                'captcha' => Module::t('labels', 'Verification Code'),
            ]
        );
    }

    /**
     * Validates this model and creates a new account for the user.
     *
     * @return boolean whether sign-up was successful.
     */
    public function signup()
    {
        if ($this->validate()) {
            $dataContract = Module::getInstance()->getDataContract();
            $account = $dataContract->createAccount(['attributes' => $this->attributes]);

            if ($account->validate()) {

                if ($account->save(false/* already validated */)) {
                    $dataContract->createPasswordHistory([
                        'accountId' => $account->id,
                        'password' => $account->password,
                    ]);

                    $auth = Yii::$app->authManager;
                    $authorRole = $auth->getRole('salon');
                    $auth->assign($authorRole, $account->getId());

                    return true;
                }
            }

            foreach ($account->getErrors('password') as $error) {
                $this->addError('password', $error);
            }

            if($errors = $account->getErrors()) {
                Yii::$app->session->setFlash('registration-errors', $errors);
            }
        }
        return false;
    }
}
