<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 25.12.2015
 * Time: 16:41
 */

use yii\helpers\Html;

?>
<div id="salon-box">
    <h2><?=Html::a('Все прокаты в '.$this->context->city_padezh, ['/site/car-rentals','city_url' => $this->context->city_url])?></h2>
    <ul class='list-salons'>
        <?php foreach($salonModels as $salon):?>
            <li>
                <h3><?=Html::a(Html::encode($salon->username), $salon->UrlSalon)?></h3>
                <div class="salon-phone"><?=(Html::encode($salon->phone))?></div>
                <div class="salon-adress"><?=(Html::encode($salon->address))?>&nbsp;</div>
            </li>
        <?endforeach?>
    </ul>
</div>