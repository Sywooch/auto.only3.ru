<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 08.11.2015
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="divDisabled" id="divStepData" style="display: block">
    <div class="" style="display: block">
        <div id="form-to-pay">
            <h2>Оплата брони</h2>

            <div class='item-form-to-pay'>
                <div class="step-row-form">
                    Период аренды:
                    <div style="display: inline-block; padding-top: 6px;">
                        с <strong><?=date('d.m.Y H:i',strtotime($rentModel->rent_from));?></strong>
                        по <strong><?=date('d.m.Y H:i',strtotime($rentModel->rent_to));?></strong>
                    </div>
                </div>
            </div>

            <div class='item-form-to-pay2'>
                <div>К оплате: <span id="paySumm"><?=$rentModel->summ?></span> руб.</div>
            </div>

            <?php
                $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'validateOnSubmit' => false,
                'validateOnBlur' => false,
                'action'=> 'https://money.yandex.ru/quickpay/confirm.xml',
                'id' => 'formpay'
                ]);
            ?>

                <input type="hidden" name="receiver" value="<?=Yii::$app->params['yandexId']?>"/>
                <input type="hidden" name="formcomment" value="Only3.ru. Прокат авто" />
                <input type="hidden" name="short-dest" value="Only3.ru. Прокат авто" />
                <input type="hidden" name="label" value="<?=$rentModel->id?>" />
                <input type="hidden" name="quickpay-form" value="donate" />
                <input type="hidden" name="targets" value="Бронирование <?=html::encode($systemModel->name)?><?=($systemModel->year ? ", ".$systemModel->year:"")?>" />
                <input type="hidden" name="sum" value="<?=$rentModel->summ?>" data-type="number" />
                <input type="hidden" name="comment" value="" />
                <input type="hidden" name="need-fio" value="false"/>
                <input type="hidden" name="need-email" value="false" />
                <input type="hidden" name="need-phone" value="false"/>
                <input type="hidden" name="need-address" value="false"/>

                <div class='row-formpay' style="background-image: url('/images/i-credit.png');">
                    <input type="radio" name="paymentType" value="AC" checked id="input-type-card" />
                    <label for="input-type-card">Банковской картой</label>
                </div>

                <div class='row-formpay' style="background-image: url('/images/i-yandex.png');">
                    <input type="radio" name="paymentType" value="PC"  id="input-type-yandex" />
                    <label for="input-type-yandex">Яндекс.Деньгами</label>
                </div>

                <div class='row-formpay rowsubmit-formpay'>
                    <?=Html::a('Назад',['cars/reserve-a-car-step2', 'id'=>$rentModel->id],['class'=>'del-success'])?>
                    <input type="submit" name="submit-button" class='bty' value="Оплатить" style="margin-left: 20px;"/>
                </div>

            </form>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
