<?php
namespace frontend\modules\account\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */

use frontend\modules\account\models\Account;

class PasswordResetRequestForm extends Model
{
    public $phone;

    public $captcha;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['phone', 'filter', 'filter' => 'trim'],
            [['phone','captcha'], 'required', 'message'=>'Введите проверочный код'],

            ['captcha', 'captcha', 'captchaAction' => 'account/auth/captcha'],

            ['phone', 'exist',
                'targetClass' => 'frontend\modules\account\models\Account',
                //'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с указанным номером телефона не найден.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'Captcha' => 'Каптча'
        ];
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }

    public function sendSMS(){

        $user = Account::findOne([
            'phone' => $this->phone,
        ]);

        if ($user) {

            $passToken = $user->generatePasswordResetToken()->getPasswordResetToken();

            $user->setScenario('reset-password');

            if ($user->save()) {
                $mes = 'only3.ru Код:'.$passToken;
                $phoneClient = preg_replace('/[^0-9]/', '', $this->phone);
                $login = 'sergey.sandalov@gmail.com';
                $password = 'samogon';
                $phone = $phoneClient;
                $from = 'only3';
                $mes = iconv("UTF-8", "WINDOWS-1251", $mes);
                $msg = urlencode($mes);
                $checksumm = md5($login . md5($password) . $phone);
                $res = file_get_contents("http://sms48.ru/send_sms.php?login=$login&to=$phone&msg=$msg&from=$from&check2=$checksumm");
                if ($res == 1) {
                    //sms was sending
                    return $user->id;
                }
            }
        }

        return false;
    }
}
