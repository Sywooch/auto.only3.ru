<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 08.11.2015
 */

namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

use yii\base\Model;

/**
 * This is the model class for table "Addphotos".
 *
 * @property integer $id
 * @property integer $system_id
 * @property string $days
 * @property string $time
 *
 * @property SystemAuto $system
 */
class Prepare extends Model
{
    public $id;
    public $name;
    public $phone;
    public $is_organization;
    public $birth_date;
    public $passport_serion;
    public $passport_number;
    public $address_reg;
    public $address_fact;
    public $license_number;
    public $license_date;
    public $inn;
    public $kpp;
    public $ogrn;

    public $okpo;
    public $bank;
    public $k_sch;

    public $r_sch;
    public $bik;
    public $director;

    public $city_id;
    public $experience;

    public function rules()
    {
        return [

            [['is_organization'], 'string'],
            [['name', 'phone', 'address_reg', 'address_fact'], 'string', 'max' => 255],
            [['experience'], 'string', 'max' => 2],
            [['city_id'], 'number'],
            [['passport_serion'], 'string', 'max' => 4],
            [['passport_number'], 'string', 'max' => 14],
            [['license_number'], 'string', 'max' => 20],
            [['ogrn'], 'string', 'max' => 20],
            [['inn', 'kpp', 'bik'], 'string', 'max' => 12],
            [['r_sch'], 'string', 'max' => 60],

            [['license_date', 'birth_date'], 'date', 'format' => 'dd.MM.yyyy'],

            [['name', 'phone', 'is_organization', 'birth_date', 'passport_serion', 'passport_number', 'address_reg', 'address_fact', 'license_number', 'license_date', 'city_id', 'experience'], 'required', 'on' => 'default'],

            [['name', 'phone', 'address_reg', 'address_fact', 'inn', 'kpp', 'ogrn', 'r_sch', 'k_sch', 'bik', 'director', 'bank' , 'okpo'], 'required', 'on' => 'jur-validate']
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['jur-validate'] = ['name', 'phone', 'address_reg', 'address_fact', 'inn', 'kpp', 'ogrn', 'r_sch', 'k_sch', 'bik', 'okpo', 'director', 'bank', 'city_id', 'experience'];
        $scenarios['client-validate'] = ['name', 'phone', 'address_reg', 'address_fact', 'inn', 'kpp', 'ogrn', 'r_sch', 'k_sch', 'bik', 'okpo', 'director', 'bank', 'city_id', 'experience'];
        return $scenarios;
    }

    public function beforeValidate(){

        if($this->is_organization == 1){
            $this->scenario = 'jur-validate';
        }

        return parent::beforeValidate();
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'phone' => 'Телефон',
            'is_organization' => 'Клиент',
            'birth_date' => 'Дата рождения',
            'passport_serion' => 'Паспорт: серия',
            'passport_number' => 'Паспорт: номер',
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

            'city_id' => 'Город',
            'experience' => 'Стаж вождения'
        ];
    }

}
