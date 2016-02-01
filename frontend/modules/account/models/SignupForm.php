<?php
namespace frontend\modules\account\models;

use frontend\modules\account\Module;

use common\models\User;
use yii\base\Model;
use Yii;
use Yii\helpers\ArrayHelper;
use frontend\models\UsersData;


/**
 * Signup form
 */
class SignupForm extends PasswordForm
{
    public $username;
    public $email;

    public $phone;
    public $city_name;
    public $url;

    public $password;
    public $verifyPassword;
    public $cleanPassword;

    public $captcha;

    public $account_id;
    public $salon_account_id;


    /**
     * @inheritdoc
     */


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['client-registration'] = ['username', 'phone'];
        $scenarios['change-password'] = ['password', 'verifyPassword'];
        return $scenarios;
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
            [['email', 'username', 'phone', 'city_name'], 'required'],
            ['username', 'string', 'min' => 4, 'max' => 255],
            ['email', 'email'],

            [['username', 'phone'], 'required', 'on' => 'client-registration'],

            ['username', 'unique', 'targetClass' => Module::CLASS_ACCOUNT, 'message' => 'Название автопроката уже занято.', 'on' => 'default'],

            ['email', 'unique', 'targetClass' => Module::CLASS_ACCOUNT, 'message' => 'Этот емайл адрес уже был занят.'],
            ['phone', 'unique', 'targetClass' => Module::CLASS_ACCOUNT, 'message' => 'Этот номер телефона уже был занят'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'string', 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'phone' => Module::t('labels', 'Телефон (логин)'),
                'email' => Module::t('labels', 'E-mail'),
                'url' => Module::t('labels', 'Ссылка на сайт'),
                'city_name' => Module::t('labels', 'Город'),
                'username' => Module::t('labels', 'Автопрокат'),
                'captcha' => Module::t('labels', 'Проверочный код'),
            ]
        );
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new Account();
            $user->setAttributes($this->getAttributes());

            $user->setPassword($this->password);
            $user->generateAuthKey();

            if ($user->save()) {

                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole('salon');
                $auth->assign($authorRole, $user->getId());

                return $user;
            }

        } else {
        }

        return null;
    }

    /**
     * @return array $reuslt['user','errors'];
     */
    public function signupClient(){

        $result = ['user'=>'','errors'=>''];
        $errors = '';
        $user = '';
        if ($this->validate()) {
            $user = new Account();
            $user->setScenario('client-registration');
            $user->setAttributes($this->getAttributes());
            $user->setAttribute('status', 1);

            $user->password =  Yii::$app->security->generateRandomString(8);
            $user->cleanPassword = $user->password;

            $user->setPassword($this->password);
            $user->generateAuthKey();

            if ($user->save()) {

                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole('client');
                $auth->assign($authorRole, $user->getId());

            } else {
                $errors['user'] = $user->getErrors();
            }

        } else {
            $errors['signup'] = $this->getErrors();
        }

        $result['user'] = $user;
        $result['errors'] = $errors;
        return $result;
    }


}
