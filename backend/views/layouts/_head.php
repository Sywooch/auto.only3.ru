<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 17.11.2015
 * Time: 13:25
 */
use yii\helpers\html;
use yii\helpers\Url;

?>
<style>

.count h3{
    text-align: center;
    font-family: 'Open Sans';
    font-weight: 300;
    color: #545454;
}

 .count div{
     font-family: 'Chewy';
     font-weight: normal;
     width: 100px;
     height: 100px;
     font-size: 58px;
     margin: auto;
     line-height: 100px;
     color: #fff;
     background-color: #52CD14;
     border-radius: 50%;
 }
</style>
<div class="row site-index">

    <div class="col-xs-3 text-center count">
        <h3><a href="<?=Url::toRoute(['account/index']);?>">Автопрокатов</a></h3>
        <div style="background-color: #52cd14">
            <?php
                echo frontend\modules\account\models\Account::getCountAccounts();
            ?>
        </div>
    </div>

    <div class="col-xs-3 text-center count">
       <h3><a href="<?=Url::toRoute(['auto/index']);?>">Автомобилей</a></h3>
       <div style="background-color: #149dcd">
            <?php
            echo frontend\models\System::getCountSystem();
            ?>
        </div>
    </div>

    <div class="col-xs-3 text-center count">
        <h3><a href="<?=Url::toRoute(['payments/index']);?>">Заявки на выплату</a></h3>
        <div style="background-color: #cbcd63">
            <?php
            echo common\models\payments\PaymentsTransaction::getCountPayments();
            ?>
        </div>
    </div>


    <div class="col-xs-3 text-center count">
        <h3><a href="<?=Url::toRoute(['black/index']);?>">Черный список клиентов</a></h3>
        <div style="background-color: #E8E8E8">
            <?php
                echo frontend\models\UsersData::getCountInBlack();
            ?>
        </div>
    </div>
</div>