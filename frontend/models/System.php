<?php

namespace frontend\models;

use common\models\AutoBrands;
use common\models\AutoModels;
use frontend\modules\profile\models\Rentact;
use frontend\models\Only3Model;

use Yii;

use frontend\modules\account\models\Account;

use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

use yii\imagine\Image;

use yii\behaviors\SluggableBehavior;

use frontend\components\ThumbImage;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * This is the model class for table "system_auto".
 *
 * @property integer $id
 * @property integer $system_id
 * @property string $category
 * @property string $mark
 * @property string $model
 * @property string $year
 * @property integer $body_id
 * @property string $wheel
 * @property string $fuel
 * @property string $volume
 * @property string $power
 * @property string $trans
 * @property string $gear
 * @property string $number
 * @property integer $model_id
 * @property string $min_time
 * @property string $min_tax
 * @property string $pledge
 * @property string $contact
 * @property string $info
 * @property string $photo
 *
 * @property AutoModels $model0
 * @property System $system
 */
class System extends Only3Model
{
    /**
     * @inheritdoc
     */

    public $file;
    public $imageFiles;

    protected $markerName = 'Auto';

    public static function tableName()
    {
        return 'system_auto';
    }


    public function getModelMarkerList(){

        $arr = [
            'name' => 'Марка',
            'year'   => 'Год выпуска',

            'reg_num' => 'Регистрационный знак',
            'dvig_num' => 'Номер двигателя',
            'kuz_num' => 'Номер кузова',
            'vin'   => 'Идентификационный номер (VIN)',
            'color' => 'Цвет',
            'pts' => 'Паспорт транспортного средства серия',
            'price' => 'Договорная стомость ТС',

           // 'min_age' => 'Минимальный возраст',
           // 'min_exp' => 'Минимальный стаж',
        ];

        return $arr;
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'name',
                'out_attribute' => 'slug_url',
                'translit' => true
            ],
            'timestamp' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'brand_id', 'model_id'], 'required'],
            ['min_cost', 'required', 'message' => 'Пожалуйста укажите хотя бы одну цену'],
            //['file', 'required', 'message' => 'Пожалуйста загрузите основное изображение'],

            [['category', 'w_driver', 'trans', 'conditioner', 'photos', 'wheel', 'gear', 'fuel','updated_at', 'created_at',], 'string'],
            [['cost1', 'cost2', 'cost8', 'min_cost', 'is_display', 'is_aviable'], 'number'],


            [['name', 'info', 'contract', 'photo', 'reg_num', 'dvig_num', 'kuz_num', 'vin', 'color', 'pts', 'price', 'min_age', 'min_exp'], 'string', 'max' => 255],
            [['pledge'], 'string', 'max' => 60],
            [['number'], 'string', 'max' => 2],
            [['year'], 'string', 'max' => 4],
            [['power'], 'string', 'max' => 3],

            [['name','info','pledge'], 'trim'],

            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, jpeg'],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, jpeg', 'maxFiles' => 10],

            [['photo', 'photos'], 'safe'],

        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'brand_id' => 'Марка авто',
            'model_id' => 'Модель авто',

            'name' => 'Название авто',
            'category' => 'Категория авто',
            'info' => 'Дополнительная информация',

            'cost1' => 'на 1 сутки',
            'cost2' => 'от 2 суток',
            'cost8' => 'от 8 суток ',
            'min_cost' => 'Минимальная цена',

            'trans' => 'Коробка передач',

            'conditioner' => 'Кондиционер',

            'w_driver' => 'Наличие водителя',

            'pledge' => 'Сумма залога',
            'contract' => 'Текст договора(если не заполнен, генерируется ссылка на only3)',

            'photo' => 'Главное изображение',
            'photos' => 'Изображения',

            'imageFiles' => 'Другие изображения',

            'year' => 'Год выпуска',
            'power' => 'Мощность (л/с)',
            'slug_url' => 'Урл адрес',

            'is_display' => 'Отображать в списке',
            'is_aviable' => 'Возможность бронирования',
            'is_moderated' => 'Модерация',

            'created_at' => 'Запись добавлена',
            'updated_at' => 'Запись изменена',

            'moderated_text' => '',

            'reg_num' => 'Регистрационный знак',
            'dvig_num' => 'Номер двигателя',
            'kuz_num' => 'Номер кузова',
            'vin'   => 'Идентификационный номер (VIN)',
            'color' => 'Цвет',
            'pts' => 'Паспорт транспортного средства серия',
            'price' => 'Договорная стомость ТС',

            'min_age' => 'Минимальный возраст',
            'min_exp' => 'Минимальный стаж',

            /*
                'wheel' => 'Руль',
                'gear' => 'Привод',
                'number' => 'Количество мест',
                'fuel' => 'Топливо',
            */
        ];
    }


    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    public function getAutoBrand()
    {
        return $this->hasOne(AutoBrands::className(), ['id' => 'brand_id']);
    }

    public function getAutoModel()
    {
        return $this->hasOne(AutoModels::className(), ['id' => 'model_id']);
    }

    public function getRentact()
    {
        return $this->hasMany(Rentact::className(), ['system_id' => 'id']);
    }

    public function getRentactActive()
    {
        return $this->hasMany(Rentact::className(), ['system_id' => 'id'])->active();
    }

    public static function getTrans(){

    }

    public static function getCategoryList($id = false){

        $result = [
            '1'=>'Легковые',
            '2'=>'Кроссовер',
            '3'=>'Микроавтобус',
            '4'=>'Такси',
            '5'=>'Лимузин',
        ];

        if($id !== false){
            $result = $result[$id];
        }

        return $result;
    }

    public function getCategoryName(){

        $categorys = System::getCategoryList();

        return ArrayHelper::getValue($categorys, $this->category);

    }

    public static function getFuelList(){
        return [
            '1'=>'Бензин',
            '2'=>'Дизель',
            '3'=>'Газ',
            '4'=>'Электричество',
        ];
    }

    public static function getTransList(){
        return [
            '1'=>'Ручная',
            '2'=>'Автомат',
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

    public static function getWheelList(){
        return [
            '1'=>'Левый',
            '2'=>'Правый',
        ];
    }

    public static function getGearList(){
        return [
            '1'=>'Передний',
            '2'=>'Задний',
            '3'=>'Полный',
        ];
    }

    public function getPhotosUrl()
    {
        if(!empty($this->photos))
            return array_unique(explode(';',$this->photos, 10));
        else return [];
    }

	public function getThumbImage($img, $w, $h){

        return ThumbImage::getThumbImage($img, $w, $h);

	}

    public function getNotBeRented(){

        $mesText = '';

        if(!$this->isNewRecord) {

            if ($this->account->balance < 0) {
                $mesText .= 'Необходимо пополнить баланс аккаунта<br/>';
            }
        }

        if (!empty($mesText)) {
            $mesText = 'Внимание автомобиль не доступен для бронирования по причине:<br/>' . $mesText;
        }

        return $mesText;
    }

    public function getIsNotShowing(){

        $mesText = '';
        if(!$this->isNewRecord) {

            if ($this->account->is_moderated <> 2) {
                $mesText .= 'Модерация '.Html::a('информации о салоне автопроката',['/profile/default/edit']).' не пройдена<br/>';
            }

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

            if ($this->is_display != 1) {
                $mesText .= 'Необходимо включить настройку "Отображать в списке"<br/>';
            }

            if (!empty($mesText)) {
                $mesText = 'Внимание автомобиль не отображается для клиентов по причине:<br/>' . $mesText;
            }
        }

        return $mesText;

    }

    static function getCountSystem()
    {
        return static::find()->count();
    }

    public function getIsShowInList(){

    }

    public function getPaymentSettings(){

        $paymentSettings = $this->account->paymentsAccount;

        if($paymentSettings){
            if($paymentSettings->is_active){
                return $paymentSettings;
            }
        }

    }

    public function getPayType(){
        $res = '';
        if($this->paymentSettings){
            $res = $this->paymentSettings->pay_type == 1 ? "calc":"fix";
        }
        return $res;
    }

    public function getPaySumm(){
        $res = '';
        if($this->paymentSettings){
            $res = $this->paymentSettings->pay_summ;
        }
        return (int)$res;
    }

    public function getIsClientPayCom(){
        if($this->paymentSettings && $this->paymentSettings->is_client_pay_com) {
            return true;
        }
        return false;
    }

    public function getPayPercent(){
        $percent = 0;
        if($this->paymentSettings) {
            $percent = ((1 - $this->paymentSettings->SystemPercent * 0.01) * (1 - $this->paymentSettings->OutPercent * 0.01));
        }
        return $percent;
    }


    public function getPayComm($payment){

        $summ = 0;
        if($this->PayPercent) {
            $summ = ($payment / $this->PayPercent) - $payment;
            $summ = round($summ, 2, PHP_ROUND_HALF_UP);
        }
        return $summ;
    }

    /**
     * @param $rent_from
     * @param $rent_to
     * @return array
     */
    public function calcPriceForRent($rent_from, $rent_to){

        $dStart = new \DateTime(date('Y-m-d', strtotime($rent_from)));
        $dEnd = new \DateTime(date('Y-m-d', strtotime($rent_to)));
        $dDiff = $dStart->diff($dEnd, true);

        $days = $dDiff->days;

        $cost = 0;

        if ($days == 1) {
            $cost = $this->cost1;
        } elseif ($days >= 2 && $days < 8) {

            if($this->cost2) {
                $cost = $this->cost2;
            } else {
                $cost = $this->cost1;
            }

        } elseif ($days >= 8) {
            if($this->cost8) {
                $cost = $this->cost8;
            } else {
                if($this->cost2) {
                    $cost = $this->cost2;
                } else {
                    $cost = $this->cost1;
                }
            }
        }

        $res = [
            'cost' => $cost,
            'days' => $days
        ];
        return $res;
    }

    /**
     * return advance payment
     * @param $rent_from
     * @param $rent_to
     * @return int|mixed
     */
    public function calcResultSumm($rent_from, $rent_to){

        $PayType = $this->PayType;
        $PaySumm = $this->PaySumm;

        $resultSumm = 0;
        if($PayType === 'calc') {

            return $this->calcfullRentSumm($rent_from, $rent_to);

        } else {
            $resultSumm = $PaySumm;
        }

        if($this->IsClientPayCom){
            $resultSumm = $resultSumm + $this->getPayComm($resultSumm);
        }

        return $resultSumm;
    }

    /**
     * return advance payment
     * @param $rent_from
     * @param $rent_to
     * @return int|mixed
     */
    public function calcOutSumm($rent_from, $rent_to){

        $PayType = $this->PayType;
        $PaySumm = $this->PaySumm;

        $resultSumm = 0;
        if($PayType === 'calc') {
            $calcRes = $this->calcPriceForRent($rent_from, $rent_to);
            return $calcRes['cost'];
        } else {
            $resultSumm = $PaySumm;
        }

        if(!$this->IsClientPayCom){
            $resultSumm = $resultSumm * $this->PayPercent;
        }

        return $resultSumm;
    }

    /**
     * return full contract summ
     */
    public function calcfullRentSumm($rent_from, $rent_to){

        $calcRes = $this->calcPriceForRent($rent_from, $rent_to);
        $cost = $calcRes['cost'];
        $days = $calcRes['days'];

        $resultSumm = $days * $cost;
        $resultSumm = $resultSumm + $this->getPayComm($resultSumm);
        return $resultSumm;

    }

    public function getIsCanPay(){

        if($this->account->IsCanPay){
            return true;
        }
        return false;
    }

    public function getPageReserve(){
        $urlReserve = Url::toRoute(['/cars/reserve-a-car',
            'city_url' => $this->account->city->trans,
            'slug_url' => $this->account->slug_url,
            'slug_url_system' => $this->slug_url]);

        return $urlReserve;
    }

    public static function getModelsList($id){
        $models = AutoModels::find()->where(['brand_id' => $id])->all();
        $str = '<option value="">-</option>';
        if(!empty($models)){
            foreach($models as $model){
                $str .= '<option value="'.$model->id.'">'.$model->name.'</option>';
            }
        }

        return $str;
    }

    public function getBrandName(){
        $name = '';
        if($this->brand_id)
            $name = AutoBrands::findOne($this->brand_id)->name;
        return $name;
    }

    public function getModelName(){
        $name = '';
        if($this->model_id)
            $name = AutoModels::findOne($this->model_id)->name;
        return $name;
    }

    public function getCarName(){

        $name = '';
        if($this->brand_id)
            $name = $name .' '. AutoBrands::findOne($this->brand_id)->name;

        if($this->model_id)
            $name = $name .' '. AutoModels::findOne($this->model_id)->name;

        if(empty($name))
            $name = $this->name;

        if($this->year)
            $name = $name .' '. $this->year.'г.';

        return $name;
    }
    /*******************************/

    public static function getAccountCountAutos($account_id){
        return System::find()->where(['account_id' => $account_id])->count();
    }

    /***************************************/

    public function beforeValidate()
    {
        $this->name = $this->BrandName. ' ' .$this->ModelName;
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $this->is_moderated = 2;

        /*
        if($this->getScenario() != 'moderate'){
            if($this->getAttributes() != $this->getOldAttributes()){
                $this->is_moderated = 0;
            }
        }
        */
        return parent::beforeSave($insert);
    }

    public function afterSave($ins, $chAttr){

        $Account = Account::findOne(['id' => $this->account_id]);
        $Account->setAttribute('count_autos', self::getAccountCountAutos($this->account_id));
        $Account->save();
        return parent::afterSave($ins, $chAttr);
    }

    public function afterDelete(){

        $Account = Account::findOne(['id' => $this->account_id]);
        $Account->setAttribute('count_autos', self::getAccountCountAutos($this->account_id));
        $Account->save();
        return parent::afterDelete();
    }


}
