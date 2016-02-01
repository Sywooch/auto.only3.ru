<?php

namespace frontend\models;


use common\behaviors\Slug;
use frontend\modules\account\models\Account;
use yii\imagine\Image;

use frontend\components\ThumbImage;

use frontend\modules\profile\models\Rentact;
use frontend\models\Only3Model;

use Yii;
use yii\db\Expression;
use Yii\helpers\Url;

use yii\web\UploadedFile;

use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "users_data".
 *
 * @property integer $id
 * @property string $fio
 * @property string $phone
 * @property string $is_organization
 * @property string $birth_date
 * @property string $passport_serion
 * @property string $passport_number
 * @property string $address_reg
 * @property string $address_fact
 * @property string $license_number
 */
class UsersData extends Only3Model
{

    protected $markerName = 'Client';

    protected $protectedImageFields = [
        'image_passport_photo',
        'image_passport_reg',
        'image_drive_licence'
    ];

    public $imageProtectedDir = '/protected/images/userdata/';

    public $user_data_id;

    public $image_passport_photo_up;
    public $image_passport_reg_up;
    public $image_drive_licence_up;

    const IN_BLACKLIST = '1';

    public $mode = 'Client';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_data';
    }

    public function getModelMarkerList()
    {

        $arr = $this->attributeLabels();
        unset($arr['id']);

        if ($this->mode === 'Client') {
            $arr = [
                'name' => 'ФИО',
                'address_reg' => 'Адрес регистрации',
                'address_fact' => 'Адрес фактический',
                'passport_serion' => 'Паспорт серия',
                'passport_number' => 'Паспорт номер',
                'passport_give' => 'Паспорт выдан',
                'birth_date' => 'Дата рождения',
                'license_number' => 'Водительское удостоверение',
                'exp' => 'Водительский стаж',
                'phone' => 'Телефон'
            ];
        } else {
            $this->markerName = 'Account';

            $arr = [
                'name' => 'ФИО',
                'passport_serion' => 'Паспорт серия',
                'passport_number' => 'Паспорт номер',
                'passport_give' => 'Паспорт выдан',

                'inn' => 'ИНН',
                'ogrn' => 'ОГРН',
                'address_reg' => 'Адрес регистрации',
                'phone' => 'Телефон',

            ];
        }

        return $arr;
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [

            [['is_organization', 'is_confirmed', 'check_fms', 'check_debt', 'check_licence', 'full_name', 'comp_name'], 'string'],
            [['comp_name', 'first_name', 'last_name', 'patronymic_name', 'name', 'phone', 'address_reg', 'address_fact', 'image_passport_photo', 'image_passport_reg', 'image_drive_licence', 'okpo', 'bank', 'director', 'passport_give', 'is_black'], 'string', 'max' => 255],

            [['black_text'], 'string', 'max' => 2000],

            [['name', 'phone', 'address_reg', 'address_fact', 'image_passport_photo', 'image_passport_reg', 'image_drive_licence', 'okpo', 'bank', 'director', 'passport_give', 'is_black', 'black_text',], 'filter', 'filter' => 'trim'],

            [['city_id'], 'number'],

            [['birth_date', 'license_date'], 'string', 'max' => 11],

            [['experience'], 'number', 'max' => 99],
            [['passport_serion'], 'string', 'min' => 4, 'max' => 4],
            [['passport_number'], 'string', 'min' => 6, 'max' => 6],

            ['passport_serion', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Серия паспорта может содержать только цифры'],
            ['passport_number', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Номер паспорта может содержать только цифры'],

            ['license_number', 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Номер водительского удостоверения может содержать только символы алфавита и цифры'],

            [['license_number', 'ogrn'], 'string', 'max' => 10],

            [['inn', 'kpp', 'bik'], 'string', 'max' => 12],
            [['r_sch', 'k_sch'], 'string', 'max' => 60],

            [['is_black'], 'validateBlack', 'skipOnEmpty' => false, 'skipOnError' => true],

            /***************client-to-black***************************/

            [['is_black'], 'string', 'skipOnEmpty' => false, 'skipOnError' => false, 'min' => 1, 'on' => 'client-to-black'],
            [['is_black', 'black_text', 'birth_date', 'first_name', 'last_name',
                'patronymic_name',
                'image_passport_photo', 'image_passport_reg', 'image_drive_licence',
                'first_name', 'passport_serion', 'passport_number', 'license_number'
            ], 'required', 'on' => 'client-to-black'],

            [['is_black'], 'string', 'skipOnEmpty' => false, 'skipOnError' => false, 'min' => 1, 'on' => 'client-to-black-godmode'],
            [['is_black', 'name'], 'required', 'on' => 'client-to-black-godmode'],

            /***************Prepare***************************/

            [['is_confirmed', 'check_fms', 'check_debt', 'check_licence'], 'required', 'skipOnEmpty' => false, 'requiredValue' => 1, 'message' => 'Расставьте все флаги проверок', 'on' => 'prepare'],
            [
                [
                    'name', 'phone', 'is_organization', 'birth_date', 'passport_serion', 'passport_number', 'address_reg',
                    'address_fact', 'license_number', 'city_id', 'experience',
                ], 'required', 'on' => 'prepare'
            ],

            [['comp_name', 'opf', 'name', 'phone', 'address_reg', 'address_fact', 'inn', 'kpp', 'ogrn', 'r_sch', 'k_sch', 'bik', 'director', 'bank', 'okpo'], 'required', 'on' => 'jur-validate-prepare'],

            /******************************************/
        ];
    }

    public function validateBlack($attribute, $params)
    {
        if ($this->is_black == self::IN_BLACKLIST and empty($this->black_text)) {
            $this->addError('is_black', 'Укажите причину добавления клиента в черный список');
            return false;
        }

        return true;
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $defAttr = $this->getAttributes();

        $blackAttr = $defAttr;
        unset($blackAttr['is_black']);
        unset($blackAttr['account_id']);
        unset($blackAttr['salon_account_id']);
        $blackAttr = array_keys($blackAttr);

        $scenarios['client-to-black'] = $blackAttr;
        $scenarios['client-to-black-godmode'] = $blackAttr;

        $scenarios['prepare'] = array_keys($defAttr);
        $scenarios['jur-validate-prepare'] = ['comp_name', 'org_form', 'name', 'phone', 'address_reg', 'address_fact', 'inn', 'kpp', 'ogrn', 'r_sch', 'k_sch', 'bik', 'okpo', 'director', 'bank', 'city_id',
            'experience',
            'is_confirmed',
            'check_fms',
            'check_debt',
            'check_licence',
        ];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя/название',
            'full_name' => 'ФИО',

            'phone' => 'Телефон',
            'is_organization' => 'Клиент',
            'birth_date' => 'Дата рождения',
            'passport_serion' => 'Паспорт: серия',
            'passport_number' => 'Паспорт: номер',
            'passport_give' => 'Паспорт: выдан',

            'address_reg' => 'Адрес регистрации',
            'address_fact' => 'Факт. адрес',
            'license_number' => 'Водит. удостоверение №',
            'license_date' => 'Дата выдачи удостоверения',

            'image_passport_photo' => 'Разворот паспорта с фото',
            'image_passport_reg' => 'Разворот паспорта с пропиской',
            'image_drive_licence' => 'Водительское удостоверение',

            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН',
            'okpo' => 'ОКПО',

            'r_sch' => 'р/сч.',

            'bank' => 'Банк',
            'k_sch' => 'к/сч.',
            'bik' => 'БИК',
            'director' => 'Директор (на осн. устава)',

            'PassportName' => 'Паспорт',
            'is_black' => 'В черном списке',
            'black_text' => 'Причина',

            'city_id' => 'Город',
            'experience' => 'Стаж вождения',

            'check_fms' => 'Проверка ФМС',
            'check_debt' => 'Проверка задолженности',
            'check_licence' => 'Проверка водит. удостоверения',
            'is_confirmed' => 'Я подтверждаю отсутствие причин в отклонении заявки',

            'opf' => 'ОПФ компании',
            'comp_name' => 'Название организации',
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'patronymic_name' => 'Отчество',

            'created_at' => 'Дата добавления'
        ];
    }

    /**
     * @inheritdoc
     * @return PaymentsAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersDataQuery(get_called_class());
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    public function getSalon()
    {
        return $this->hasOne(Account::className(), ['id' => 'salon_account_id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }


    public function getCityName()
    {
        $parent = $this->city;
        return $parent ? $parent->name : '';
    }

    public function getSalonName()
    {
        if ($this->salon)
            return $this->salon->username;
        return '';
    }

    public function getSalonCityName()
    {
        if ($this->salon)
            return $this->salon->city_name;
        return '';
    }

    public function getSalonPhone()
    {
        if ($this->salon)
            return $this->salon->phone;
        return '';
    }

    public function getPassportName()
    {
        return $this->passport_serion . ' ' . $this->passport_number;
    }

    public function getExp()
    {

        $res = '';
        if ($this->license_date) {
            $date = date('Y', strtotime($this->license_date));
            $res = date('Y') - $date;
        }

        return $res;
    }

    public function getRentact()
    {
        return $this->hasOne(Rentact::className(), ['user_data_id' => 'id']);
    }


    public function getThumbSecretPhoto($userdataid, $file, $w, $h)
    {

        if ($this->isCanGetPhoto()) {
            $imagePath = $this->imageProtectedDir  . $userdataid . '/' . $file;
            $resImage = Image::thumbnail('@webroot' . $imagePath, $w, $h);
            return $resImage;
        }

        return false;
    }

    public function getPhotoImageUrl($attr, $w = '1200', $h = '1200')
    {

        $file = false;
        if ($this->$attr) {
            $file = $this->$attr;
        }

        if ($file && $this->isCanGetPhoto()) {

            $id = false;
            if ($this->id) {
                $id = $this->id ? $this->id : $this->salon_account_id;
            } elseif ($this->salon_account_id) {
                $id = Yii::$app->user->identity->userData->id;
            }

            if ($id)
                return Url::toRoute(['/site/get-protected-image', 'userdataid' => $id, 'file' => $file, 'w' => $w, 'h' => $h]);

            return '';
        }

        return false;
    }

    public function isCanGetPhoto()
    {

        if ($this->account_id === Yii::$app->user->id || $this->salon_account_id === Yii::$app->user->id) {
            return true;
        }
        return false;
    }

    /**
     *
     * create userdata from account info
     * @param $client_id
     * @return UsersData
     */
    public static function createUserDataFromAccount($client_id)
    {

        $Account = Account::findOne($client_id);

        $UserData = new UsersData();
        $UserData->loadDefaultValues();
        $UserData->setAttribute('account_id', $Account->id);
        $UserData->setAttribute('salon_account_id', null);
        $UserData->setAttribute('name', $Account->username);
        $UserData->setAttribute('phone', $Account->phone);

        $UserData->save();

        return $UserData;
    }


    public static function createUserDataToSalon($client_id, $salon_id)
    {

        $Account = Account::findOne($client_id);

        $UserData = new UsersData();
        $UserData->loadDefaultValues();
        $UserData->setAttribute('account_id', $Account->id);
        $UserData->setAttribute('salon_account_id', $salon_id);
        $UserData->setAttribute('name', $Account->username);
        $UserData->setAttribute('phone', $Account->phone);

        $UserData->save();

        return $UserData;
    }

    /**
     * get current ident userdata to reserve
     *
     * если пользователь уже офорлял аренду для этого салона то берем их
     * @param $systemId
     * @param $userId
     * @param $onlyCurrent set only current salon
     */
    public static function getUserDataForReserve($client_id, $salon_id, $onlyCurrent = false)
    {

        //ищем зарегистрированные у салона
        $UserData = self::find()
            ->where(['account_id' => $client_id, 'salon_account_id' => $salon_id])
            ->one();

        if (empty($UserData)) {
            //ищем текущий пользовательский профиль
            $UserData = self::find()
                ->where(['account_id' => $client_id, 'salon_account_id' => null])
                ->one();

            if (empty($UserData)) {//не нашли создаем текущие данные пользователя
                $UserData = self::createUserDataFromAccount($client_id);
            }

            $UserData = self::createUserDataToSalon($client_id, $salon_id);
        }

        return $UserData;

    }

    public function isConfirmed()
    {

        if ($this->is_confirmed == 1)
            return true;

        return false;
    }

    /**
     * find user userData from salon userData
     *
     * @param $account_id
     * @return null|static
     */
    public function findUserData()
    {
        return UsersData::findOne(['account_id' => $this->account_id, 'salon_account_id' => null]);
    }

    /**
     * find all not confirmed userData for salon
     *
     * @param $account_id
     * @return null|static
     */
    public function findAllUserDataNC()
    {
        return UsersData::findAll(['account_id' => $this->account_id, 'salon_account_id' => $this->salon_account_id, 'is_confirmed' => '0']);
    }

    public function loadImages($arr = false)
    {

        if (empty($arr)) {
            $arr = [];
            $arr['image_passport_photo'] = UploadedFile::getInstance($this, 'image_passport_photo');
            $arr['image_passport_reg'] = UploadedFile::getInstance($this, 'image_passport_reg');
            $arr['image_drive_licence'] = UploadedFile::getInstance($this, 'image_drive_licence');
        }

        $isUploaded = [];
        foreach ($arr as $attr => $file) {

            if (!empty($file)) {
                $model = new UploadUserDataImage();
                $model->imageFile = $file;

                if ($this->id) {
                    $model->userDataId = $this->id;
                } else {
                    $model->userDataId = Yii::$app->user->identity->userData->id;
                }

                if ($model->upload()) {
                    $this->$attr = $model->fileName;
                    $isUploaded[$attr] = $this->$attr;
                } else {
                    $this->$attr = '';
                }
            }
        }

        if (!empty($isUploaded)) {
            $this->setAttributes($isUploaded);
        }


        return $isUploaded;
    }


    public function canDelete()
    {
        return true;
    }

    public function setDeleted()
    {
        $this->setAttribute('deleted', '1');
        if ($this->save()) {
            return true;
        }
        return false;
    }

    /**
     * finding userdata in black
     * @return mixed
     */
    public function findInBlack()
    {

        if ($this->name or $this->license_number or ($this->passport_serion && $this->passport_number)) {

            $inBlack = UsersData::find()->inBlack();

            $passSer = [];
            if ($this->passport_serion and $this->passport_number) {
                $passSer = ['AND',
                    ['passport_serion' => $this->passport_serion],
                    ['passport_number' => $this->passport_number],
                ];
            }

            $inBlack = $inBlack->andFilterWhere(
                ['OR',
                    ['license_number' => $this->license_number],
                    ['LIKE', 'name', $this->name],
                    $passSer
                ]
            );


            $inBlack = $inBlack->all();

            return $inBlack;
        }

        return false;
    }

    public static function getCountInBlack()
    {
        return UsersData::find()->inBlack()->count();
    }

    public function getUserName()
    {

        if (empty($this->name))
            return $this->full_name;

        return $this->name;
    }

    private function relocateImages(){

       $fromDir = $this->imageProtectedDir . Yii::$app->user->identity->userData->id . '/';
       $toDir =  $this->imageProtectedDir . $this->id . '/';

        foreach($this->protectedImageFields as $attr){

            $file = $this->$attr;
            if($file){
                $model = new UploadUserDataImage();
                $res = $model->relocate($file, $fromDir, $toDir);
            }
        }

    }

    /**/
    public function beforeSave($insert)
    {

        if ($this->last_name)
            $this->full_name = $this->last_name;

        if ($this->first_name)
            $this->full_name .= ' ' . $this->first_name;

        if ($this->patronymic_name)
            $this->full_name .= ' ' . $this->patronymic_name;

        if (empty($this->full_name))
            $this->full_name = $this->name;

        if (!empty($this->license_number)) {
            $this->license_number = mb_strtoupper($this->license_number, 'UTF-8');
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $attr)
    {
        if($insert){
            if($this->getScenario()=='client-to-black'){
                $this->relocateImages();
            }
        }

        return parent::afterSave($insert, $attr);
    }
}