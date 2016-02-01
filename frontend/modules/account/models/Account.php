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

use frontend\models\UsersData;
use frontend\modules\account\Module;

use frontend\modules\profile\models\confirm\Contract;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\IdentityInterface;

use frontend\models\Only3Model;

use frontend\models\City;

use frontend\modules\profile\models\SystemAuto;
use common\models\payments\PaymentsAccount;
/**
 * This is the model class for table "account".
 *
 * @property integer $id
 * @property string $username
 * @property string $passwordHash
 * @property string $authKey
 * @property string $email
 * @property string $lastLoginAt
 * @property string $createdAt
 * @property integer $status
 *
 * @property string $password write-only password
 *
 */
class Account extends Only3Model implements IdentityInterface
{
    protected $markerName = 'Account';

    public $timeLinux;
    public $ContractText;

    public $cleanPassword;

    const ROLE_USER = 1;
    const ROLE_SALON = 10;
    const ROLE_ADMIN = 20;

    const STATUS_ACTIVE = 1;

    const FLAG_MODERATE_COMPLET = 2;
    const FLAG_MODERATE_WAIT = 0;
    const FLAG_MODERATE_CANCELLED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }



    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['client-registration'] = ['username', 'phone'];
        $scenarios['reset-password'] = ['password_reset_token'];
        $scenarios['moderate'] = ['is_moderated', 'moderated_text', 'moderated_data'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'phone', 'city_name'], 'required'],

            [['username', 'phone'], 'required', 'on' => 'client-registration'],

            [['password_reset_token'], 'required', 'on' => 'reset-password'],

            [['email', 'phone'], 'unique'],
            [['username',], 'unique', 'on' => 'default'],

            [['status', 'city_id'], 'integer', 'integerOnly' => true],
            [['username', 'password', 'authKey', 'email', 'phone', 'is_salon', 'contract_file'], 'string', 'max' => 255],
            [['moderated_data'], 'string'],

            [['lastLoginAt', 'url', 'xy', 'address', 'place_delivery', 'other', 'contract'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('labels', 'ID'),
            'phone'=> Module::t('labels', 'Телефон'),
            'username' => Module::t('labels', 'Название салона'),
            'password' => Module::t('labels', 'Пароль'),
            'authKey' => Module::t('labels', 'Ключ авторизации'),
            'email' => Module::t('labels', 'E-mail'),
            'lastLoginAt' => Module::t('labels', 'Последний вход'),
            'createdAt' => Module::t('labels', 'Дата регистрации'),
            'updatedAt' => Module::t('labels', 'Дата изменения'),
            'city_name'=> Module::t('labels', 'Город'),
            'status' => Module::t('labels', 'Статус'),
            'url' => Module::t('labels', 'Ссылка на сайт'),
            'address' => Module::t('labels', 'Адрес'),
            'xy' => Module::t('labels', 'Координаты'),
            'balance' => Module::t('labels', 'Баланс'),
            'other' => Module::t('labels', 'Прочая информация'),

            'place_delivery' => Module::t('labels', 'Место доставки'),
            'contract' => Module::t('labels', 'Договор'),
            'ContractText' => Module::t('labels', 'Текст договора'),
            'Contract_file' => 'Загрузить договор'
        ];
    }

    public function getModeratedAttributes(){

        return [
            'username',
            'phone',
            'email',
            'place_delivery',
            'other',
            'city_name',
            'url',
            'address',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {

//        $passwordBehaviorClass = Module::getInstance()->getClassName(Module::CLASS_PASSWORD_BEHAVIOR);
//        $attribute = Module::getInstance()->passwordAttribute;

        $passwordBehaviorClass = 'nord\yii\account\behaviors\PasswordAttributeBehavior';
        $attribute = 'password';

        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'username',
                'out_attribute' => 'slug_url',
                'translit' => true
            ],
//           [
//                'class' => $passwordBehaviorClass,
//                'attribute' => $attribute,
//            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'createdAt',
                    ActiveRecord::EVENT_AFTER_UPDATE => 'updatedAt'
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function getModerateList(){

        $result = [
            '0'=>'Ожидает',
            '1'=>'Отклонено',
            '2'=>'Проверено',
        ];

        return $result;
    }

    public function getModerate(){
        $result = self::getModerateList();
        return $result[$this->is_moderated];
    }


    public static function find()
    {
        return new AccountQuery(get_called_class());
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }


    public function getSystemAutos()
    {
        return $this->hasMany(SystemAuto::className(), ['account_id' => 'id']);
    }

    public function getUserData()
    {
        //return $this->hasOne(UsersData::className(), ['account_id' => 'id', 'salon_account_id' => 'id']);
        return UsersData::findOne(['account_id' => $this->id, 'salon_account_id' => null]);
    }


    public function getPaymentsAccount()
    {
        return $this->hasOne(PaymentsAccount::className(), ['account_id' => 'id']);
    }

    static function getCountAccounts()
    {
        return static::find()->salons()->count();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public function findByPasswordResetToken($token)
    {
        $userToken = $this->password_reset_token;

        if (!static::isPasswordResetTokenValid($userToken)) {
            return null;
        }

        return static::findOne([
            'id' => $this->id,
            'password_reset_token' => $userToken,
            //'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) explode('_', $token)[1];
        $expire = Yii::$app->params['account.passwordResetTokenSmsExpire'];

        return $timestamp + $expire >= time();
    }


    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {

        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    public function generateStringNumber(){
        return rand(10000, 99999);
    }
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = $this->generateStringNumber(0) . '_' . time();
        return $this;
    }

    public function getPasswordResetToken()
    {
        return explode('_',$this->password_reset_token)[0];
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getIsCanPay(){

        if($this->paymentsAccount){
            //return $this->paymentsAccount->is_moderated == '2';
            return $this->paymentsAccount->is_active == 1;
        }
        return false;
    }


    /**
     * @return string
     */
    public function getNotBeRented(){

        $mesText = '';
        if(!$this->isNewRecord) {

            if ($this->balance < 0) {
                $mesText .= 'Необходимо пополнить баланс аккаунта<br/>';
            }
        }

        if (!empty($mesText)) {
            $mesText = 'Внимание автомобиль не доступен для бронирования по причине:<br/>' . $mesText;
        }

        return $mesText;
    }

    /**
     * @return string
     * show or not cars account
     */
    public function getIsNotShowing(){

        $mesText = '';
        if(!$this->isNewRecord) {

            if ($this->is_moderated <> 2) {
                $mesText .= '<br/>Модерация не пройдена';

                if ($this->is_moderated == '0') {
                    $mesText .= "<br/>Статус - ожидает модерации.";
                } else {
                    if (!empty($this->moderated_text)) {
                        $mesText .= "<br/>Что нужно исправить: " . $this->moderated_text . '<br/>';
                    }
                }
            }

            if (!empty($mesText)) {
                $mesText = 'Внимание автомобили и данные салона не отображаются для клиентов по причине:<br/>' . $mesText;
            }
        }

        return $mesText;

    }

    public function getLastModeratedValue(){

        $oldAttr = unserialize($this->moderated_data);
        $modAttr = $this->getModeratedAttributes();

        if(empty($modAttr))
            return [];

        if(empty($oldAttr))
            return [];


        $result = [];
        foreach($this->getAttributes() as $attr => $value){
            if(in_array($attr, $modAttr) && $oldAttr[$attr] <> $value){
                if(empty($oldAttr[$attr]))
                    $oldAttr[$attr] = 'Не задано';

                $result[$attr]['old_val'] = $oldAttr[$attr];
                $result[$attr]['new_val'] = $value;
                $result[$attr]['label'] = $this->getAttributeLabel($attr);
            }
        }

        return $result;
    }

    public function getDefaultContract(){
        $fileText = file_get_contents(Yii::getAlias('@app/modules/profile/defaultContract.txt'));
        return $fileText;
    }


    public function getContractText(){
        return Contract::getContractFullText();
    }


    public static function createUserData($client_id){

        $Account = Account::findOne($client_id);

        if($Account) {
            $UserData = new UsersData();
            return $UserData::createUserDataFromAccount($client_id);
        } else {
            return false;
        }
    }

    /**
     * return contract filename
     */
    public function getContractNameFile(){
        $res = Contract::getContractFileName();
        return $res;
    }

    /**
     * return contract filepath
     */
    public function getContractFilePath(){
        $res = Contract::getContractFilePath();
        return $res;
    }

    public function getUrlSalon(){
       return Url::toRoute(['/cars/our-cars', 'city_url' => $this->city->trans, 'slug_url' => $this->slug_url]);
    }

    public static function getSalonsListModels($city_name = false, $top = false){

        if($top){
            return Account::find()->andFilterWhere(
                [
                    "is_moderated" => '2',
                    "city_name" => $city_name,
                ])->andWhere('balance > "-1"')->andWhere('count_autos > "0"')->limit(6)->orderBy(['balance' => SORT_DESC, 'username' => SORT_ASC])->all();
        } else {
            return Account::find()->andFilterWhere(
                [
                    "is_moderated" => '2',
                    "city_name" => $city_name,
                ])->orderBy(['balance' => SORT_DESC, 'username' => SORT_ASC])->all();
        }
    }



    /**********************************************************************/
    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {

        if(!empty($this->city_name)) {
            $city = City::find()->where(['name' => $this->city_name])->one();
            if (!empty($city)) {
                $this->city_id = $city->id;
            }
        }

        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        if($this->isNewRecord) {

            if ($this->getScenario() === 'client-registration') {
                $this->slug_url = '';
            } else {
                $this->is_salon = '1';
            }

        } else {

            if ($this->getScenario() == 'moderate') {

                $oldAttr = $this->getAttributes();
                foreach($oldAttr as $attr => $value){
                    if(!in_array($attr, $this->ModeratedAttributes)){
                        unset($oldAttr[$attr]);
                    }
                }

                if($this->is_moderated <> Account::FLAG_MODERATE_COMPLET) {
                    $this->moderated_data = serialize($oldAttr);//saved last check data
                } else {
                    $this->moderated_data = null;
                    $this->moderated_text = null;
                }

            } else {

                if($this->is_moderated == Account::FLAG_MODERATE_CANCELLED) {

                    $modAttrCheckListAr = $this->getModeratedAttributes();

                    $oldAttr = $this->getOldAttributes();
                    $newAttr = $this->getAttributes();

                    foreach ($this->getAttributes() as $attr => $value) {
                        if (in_array($attr, $modAttrCheckListAr) && $oldAttr[$attr] <> $value) {
                            $this->setAttribute('is_moderated', Account::FLAG_MODERATE_WAIT);
                            break;
                        }
                    }
                }
            }
        }

        /*
        if ($this->getScenario() === 'default') {
            if(empty($this->contract)){
                $this->contract = $this->contractText;
            }
        }
        */
        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes)
    {

        if(empty($this->userData)) {
            self::createUserData($this->id);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

}
