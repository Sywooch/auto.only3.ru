<?php
namespace frontend\modules\account\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    const SCENARIO_CHANGE = 'change';

    public $password;
    public $password_reset_token;
    public $password_confirm;
    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */

    public function __construct($user)
    {
        $this->_user = $user;
        if (!$this->_user) {
            throw new InvalidParamException('Пользователь не найден');
        }
    }

    public function scenarios()
    {
        return ArrayHelper::merge(
            [
                self::SCENARIO_CHANGE => ['password', 'password_confirm'],
            ],
            parent::scenarios()
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
            [['password','password_reset_token', 'password_confirm'], 'required'],
            ['password_reset_token', 'string', 'min' => 5, 'max' => 5],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password_reset_token' => 'Код сброса пароля',
            'password' => 'Новый пароль',
            'password_confirm' => 'Повторите новый пароль',
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        Yii::$app->session->set('registered_password', $this->password);

        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
