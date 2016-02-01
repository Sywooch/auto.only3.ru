<?php
/**
* Created by Alex Semenov hejvkt@yandex.ru.
*/

use yii\helpers\Html;

$RentacModel = $model->rentact;
$systemModel = $model->rentact->system;
$PaymentAccount = $systemModel->PaymentSettings;
/*
?>
<div class="ti-photo"><? echo Html::img($systemModel->getThumbImage($systemModel->photo, 60, 45), ['width' => '60px']) ?></div>
<div class="ti-auto"><?= $systemModel->name?> <span><?= $systemModel->account->username?></span></div>
<div class="ti-time"><?=date('H:i',strtotime($model->created_at))?><br><?=date('d.m.Y',strtotime($model->created_at))?></div>
<div class="ti-vivod"><?=$PaymentAccount->OutTypeLabel?>: <?=$PaymentAccount->OutTypeVal?></div>
<div class="ti-type"><?=$PaymentAccount->pay_summ?> руб</div>
<div class="ti-comiss"><?=($PaymentAccount->is_client_pay_com ? 'на клиенте' : 'на сервисе')?></div>
<div class="ti-im"><img src="/images/s3.png"></div>
<div class="ti-sts">
    <form><select>
            <option value="0">Выбрать...</option>
            <option value="1">Одобрить</option>
            <option value="2">Отклонить</option>
        </select>
        <input type="submit" value="Ок">
    </form>
    <div>одобрено</div>
</div>
<div class="clear"></div>

<div class="rentact-info">
    <div class="r-info">
        <div class="r-info-date" title="<?=$RentacModel->userData->name?>"><span>Контактное лицо: </span> <?=$RentacModel->userData->name?></div>
        <div class="r-info-name" title="<?=$RentacModel->userData->phone?>"><span>Телефон: </span> <?=$RentacModel->userData->phone?></div>
        <div class="r-info-phone"><span>Город:</span> <?=$systemModel->account->city_name?></div>
        <div class="clear"></div>
    </div>
</div>

*/?>
<li class="t-item no-pay">
    <div class="ti-photo"><img src="http://auto.only3.ru/images/_thumbs/60/45/uploads/14/1f0e0e335fe82c30acdc3f5d63daf068.jpg"></div>
    <div class="ti-auto">Chevrolet Cruze <span>Автопрокат Комильфо</span></div>
    <div class="ti-time">10:05<br>27.10.2015</div>
    <div class="ti-op">№49935342364651232</div>
    <div class="ti-rc">БК: 4276 5500 6981 1286</div>
    <div class="ti-all">
        <span class="stp1">7260 руб оплачено</span>
        <span class="stp2">7115 руб поступило</span>
        <span class="stp3">7065 руб к выплате</span>
    </div>
    <div class="ti-st"> <span class="nosuccess-label">не выплачено</span><a href="#">Я оплатил(а)</a></div>
    <div class="clear"></div>

    <div class="rentact-info">
        <div class="r-info">
            <div class="r-info-date"><span>Период бронирования: </span> 20.11.2015 - 25.11.2015</div>
            <div class="r-info-name"><span>ФИО: </span> Иван Иванов</div>
            <div class="r-info-phone"><span>Телефон:</span>+7 (645) 375-67-34</div>
            <div class="clear"></div>
        </div>
    </div>
</li>
