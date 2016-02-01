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
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Expression;

class LoginForm extends Model
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var boolean
     */
    public $rememberMe = true;

    /**
     * @var Account
     */
    private $_account;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword', 'skipOnError' => true],
//            ['password', 'validateAccountActivated', 'skipOnError' => true],
//            ['password', 'validateAccountNotLocked', 'skipOnError' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Module::t('labels', 'Телефон'),
            'email' => Module::t('labels', 'E-mail'),
            'password' => Module::t('labels', 'Пароль'),
            'rememberMe' => Module::t('labels', 'Запомнить меня'),
            'name' => Module::t('labels', 'Имя пользователя'),
        ];
    }

    /**
     * Validates the password.
     *
     * @param string $attribute validated attribute.
     * @param array $params additional parameters.
     */
    public function validatePassword($attribute, $params)
    {
        $account = $this->getAccount();

        if ($account === null || !$account->validatePassword($this->password)) {
            $this->addError($attribute, Module::t('errors', 'Неверные данные авторизации'));
        }
    }

    /**
     * Validates that the account is activated.
     *
     * @param string $attribute validated attribute.
     * @param array $params additional parameters.
     */
    public function validateAccountActivated($attribute, $params)
    {
        $account = $this->getAccount();
        if ($account !== null && !Module::getInstance()->getDataContract()->isAccountActivated($account)) {
            $this->addError($attribute, Module::t('errors', 'Учетная запись пока не активирована'));
        }
    }

    /**
     * Validates that the account is not locked.
     *
     * @param string $attribute validated attribute.
     * @param array $params additional parameters.
     */
    public function validateAccountNotLocked($attribute, $params)
    {
        $account = $this->getAccount();
        if ($account !== null && Module::getInstance()->getDataContract()->isAccountLocked($account)) {
            $this->addError($attribute,
                Module::t('errors', 'Ваша учетная запись заблокирована из-за большого числа неудачных попыток входа.'));
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            $account = $this->getAccount();

            $duration = $this->rememberMe ? Module::getParam(Module::PARAM_LOGIN_EXPIRE_TIME) : 0;
            $success = Yii::$app->user->login($account, $duration);

            $account->updateAttributes(['lastLoginAt' => new Expression('NOW()')]);
            //$this->createHistoryEntry($account, $success);

            return $success;
        } else {
            return false;
        }
    }

    /**
     * Creates a login history entry.
     *
     * @param ActiveRecord $account account instance.
     * @param boolean $success whether login was successful.
     */
    protected function createHistoryEntry(ActiveRecord $account, $success)
    {
        $dataContract = Module::getInstance()->getDataContract();
        $dataContract->createLoginHistory([
            'accountId' => $account->getPrimaryKey(),
            'success' => $success,
            'numFailedAttempts' => $success ? 0 : $dataContract->getAccountNumFailedLoginAttempts($account),
        ]);
    }

    /**
     * Returns the account associated with the value of the login attribute.
     *
     * @return Account model instance.
     */
    public function getAccount()
    {
        if ($this->_account === null) {
            $this->_account = Account::findOne([Module::getInstance()->phoneAttribute => $this->phone]);
        }

        return $this->_account;
    }
}
