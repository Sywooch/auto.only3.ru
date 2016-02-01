<?php

namespace common\models\payments;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use frontend\modules\account\models\Account;

/**
 * This is the model class for table "payments_account".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $is_active
 * @property string $card_number
 * @property string $r_sch
 * @property string $yandex_money
 * @property string $out_type
 * @property string $pay_type
 * @property string $is_client_pay_com
 * @property string $is_moderated
 * @property string $moderated_text
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Account $account
 */
class PaymentsAccount extends \frontend\models\Only3Model
{

    const YandexPercent = 2;
    const OnlyPercent = 1;//процент который берем мы

    const OUT_TYPE_BANK = 1;
    const OUT_TYPE_RS = 2;
    const OUT_TYPE_YANDEX = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments_account';
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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'pay_type', 'pay_summ'], 'integer'],
            [['is_active', 'out_type', 'pay_type', 'is_client_pay_com', 'is_moderated', 'rs_bik', 'rs_fio', 'rs_ident'], 'string'],

            [['created_at', 'updated_at'], 'safe'],
            [['card_number', 'r_sch', 'yandex_money'], 'string', 'max' => 255],
            [['card_number', 'r_sch', 'yandex_money'], 'filter', 'filter' => 'trim'],

            ['pay_summ', 'validatePayVal', 'skipOnEmpty' => false, 'skipOnError' => false],

            [['moderated_text'], 'string', 'max' => 2000]
        ];
    }

    public function validatePayVal($attribute, $params)
    {
        if($this->pay_type == 2 && $this->$attribute < 1){
            $this->addError($attribute, 'Укажите корректную сумму аванса');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'is_active' => 'Подключить',
            'card_number' => 'Номер банковской карты',
            'r_sch' => 'Расчетный счет',
            'yandex_money' => 'Яндекс кошелек',
            'out_type' => 'Способ вывода',
            'pay_type' => 'Тип оплаты за авто(аванс/расчет)',
            'pay_summ' => 'Сумма аванса',
            'is_client_pay_com' => 'Переложить на клиента',
            'is_moderated' => 'Промодерировано',
            'moderated_text' => 'Заявка отклонена по причине',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * @inheritdoc
     * @return PaymentsAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentsAccountQuery(get_called_class());
    }

    public static function getIdentList(){

        $result = [
            '1'=>'Банковкий счет',
            '2'=>'Лицевой счет',
            '3'=>'Договор',
            '4'=>'Карточный счет',
            '5'=>'Карта',
        ];

        return $result;
    }


    public function getSystemPercent(){

        return self::YandexPercent + self::OnlyPercent;
    }

    public function getOnlyPercent(){
        return self::OnlyPercent;
    }

    public function getOutPercent(){

        switch ($this->out_type){
            case self::OUT_TYPE_BANK:
                $percent = 7;
            break;

            case self::OUT_TYPE_RS:
                $percent = 5;
            break;

            case self::OUT_TYPE_YANDEX:
                $percent = 3;
            break;
        }

        return $percent;
    }

    public static function outTypeLabel(){

        return array(
            self::OUT_TYPE_BANK => 'БК',
            self::OUT_TYPE_RS => 'Р/С',
            self::OUT_TYPE_YANDEX => 'ЯК',
        );
    }

    public function getOutTypeVal(){

        switch($this->out_type){
            case(self::OUT_TYPE_BANK):
                return $this->card_number;
            break;
            case(self::OUT_TYPE_RS):
                return $this->r_sch;
            break;
            case(self::OUT_TYPE_YANDEX):
                return $this->yandex_money;
            break;
        }
    }

    public function getOutTypeLabel(){

        return self::outTypeLabel()[$this->out_type];
    }

    /***********************actions**********************************/

    public function beforeSave($ins){

        $this->is_moderated = 2;

        if($this->isNewRecord){
            $this->account_id = (int)Yii::$app->user->id;
        }

        return parent::beforeSave($ins);
    }

}
