<?php

namespace common\models\payments;

use Yii;
use frontend\modules\profile\models\Rentact;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "payments_transaction".
 *
 * @property string $id
 * @property integer $rentact_id
 * @property string $operation_id
 * @property double $amount
 * @property double $amount_all
 * @property string $datetime
 * @property string $time
 * @property string $sender
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Rentact $rentact
 */
class PaymentsTransaction extends \frontend\models\Only3Model
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    public function transactions()
    {
        return [
            'default' => self::OP_ALL
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rentact_id', 'operation_id', 'amount', 'amount_all'], 'required'],
            [['rentact_id',], 'integer'],
            [['amount', 'amount_all'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['operation_id', 'pay_out'], 'string', 'max' => 200],
            [['datetime', 'time', 'sender'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rentact_id' => 'Rentact ID',
            'operation_id' => 'Operation ID',
            'amount' => 'Amount',
            'amount_all' => 'Amount All',
            'datetime' => 'Datetime',
            'time' => 'Time',
            'sender' => 'Sender',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentact()
    {
        return $this->hasOne(Rentact::className(), ['id' => 'rentact_id']);
    }

    /**
     * @inheritdoc
     * @return PaymentsTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentsTransactionQuery(get_called_class());
    }

    public function createPaymentTransaction()
    {
        $errors = '';
        if($this->save()){
            $rentact = $this->rentact;
            $rentact->setIsPayed();
            if($rentact->save()){
                return true;
            } else {
                $errors = $rentact->getErrors();
            }
        }else{
            $errors = $this->getErrors();
        }

        return $errors;
    }


    public function getAmountPayOut(){

        $OutPercent = $this->rentact->system->PaymentSettings->OnlyPercent;
        $resSum = $this->amount * (100-$OutPercent) * 0.01;
        $resSum = round($resSum, 2, PHP_ROUND_HALF_UP);

        return $resSum;
    }

    static function getCountPayments(){
        return static::find()->count();
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }
}
