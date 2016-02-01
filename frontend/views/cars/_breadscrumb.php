<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row bread">
    <div class="col-xs-8 no-offset">
        <ul style="width: 570px;float: left;" id="list-bread">
            <li><a href="<?=Url::toRoute(['site/index', 'city_url' => $model->account->city->trans])?>">Прокат авто в <?=html::encode($model->account->city->padezh)?></a><span> &gt; </span></li>
            <li><a href="<?=Url::toRoute(['cars/our-cars', 'city_url'=>$model->account->city->trans, 'slug_url' => $model->account->slug_url])?>">Автопрокат <?=html::encode($model->account->username)?></a><span> &gt; </span></li>
            <li><?=$model->name?></li>
        </ul>
    </div>
    <div class="col-xs-4 right-bread ">
        <span style="color: #767676;"><?=html::encode($model->account->address)?></span>&nbsp;|&nbsp; г.<?=html::encode($this->context->city)?>
    </div>
</div>
