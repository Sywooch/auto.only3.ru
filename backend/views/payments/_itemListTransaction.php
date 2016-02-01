<?php
/**
* Created by Alex Semenov hejvkt@yandex.ru.
*/

use yii\helpers\Html;
use yii\helpers\Url;

$RentacModel = $model->rentact;
$systemModel = $model->rentact->system;
$PaymentAccount = $systemModel->PaymentSettings;
?>

<li class="t-item <?=($model->pay_out == 1)? "no-pay":"yes-pay" ?>">
    <div class="ti-photo"><?= Html::img($systemModel->getThumbImage($systemModel->photo, 60, 45), ['width' => '60px']) ?></div>
    <div class="ti-auto"><?= $systemModel->name?><span> прокат: <?= $systemModel->account->username?></span></div>
    <div class="ti-time"><?=date('H:i',strtotime($model->created_at))?><br><?=date('d.m.Y',strtotime($model->created_at))?></div>
    <div class="ti-op">№<?=$model->operation_id?></div>
    <div class="ti-rc"><?=$PaymentAccount->OutTypeLabel?>: <?=$PaymentAccount->OutTypeVal?></div>
    <div class="ti-all">
        <span class="stp1"><?=$model->amount_all?> руб оплачено</span>
        <span class="stp2"><?=$model->amount?> руб поступило</span>
        <span class="stp3"><?=$model->AmountPayOut?> руб к выплате</span>
    </div>
    <div class="ti-st">
        <?if($model->pay_out == 2):?>
            <span class="success-label">выплачено</span>
        <?else:?>
            <span class="nosuccess-label">не выплачено</span>
            <?=Html::a(
                'Я оплатил(а)',
                Url::toRoute(['pay-out','id'=>$model->id]),[
                    'title' => 'пометить выплаченным',
                    'data-confirm' => Yii::t('yii', 'Вы действительно хотите подтвердить факт оплаты?'),
                    'data-method' => 'post',
                    'data' => ['id'=>$model->id],
                ]
            );?>
        <?endif?>
    </div>
    <div class="clear"></div>

    <div class="rentact-info">
        <div class="r-info">
            <div class="r-info-date"><span>Период бронирования: </span> <?=date('d.m.Y',strtotime($RentacModel->rent_from))?> - <?=date('d.m.Y',strtotime($RentacModel->rent_to))?></div>
            <div class="r-info-name"><span>ФИО: </span> <?=$RentacModel->userData->name?></div>
            <div class="r-info-phone"><span>Телефон: </span><?=$RentacModel->userData->phone?></div>
            <div class="clear"></div>
        </div>
    </div>
</li>
