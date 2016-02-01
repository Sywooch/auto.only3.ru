<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row bread">
    <div class="col-xs-8 no-offset">
        <ul style="width: 500px;float: left;" id="list-bread">
            <li><a href="<?=Url::toRoute(['site/index', 'city_url' => $this->context->city_url])?>">Прокат авто в <?=html::encode($this->context->city_padezh)?></a><span> &gt; </span></li>
            <li><?=html::encode($model->username)?></li>
        </ul>
    </div>
    <div class="col-xs-4 right-bread ">
       <!--   <span style="color: #767676;"><?=html::encode($model->address)?></span>&nbsp;|&nbsp; г.<?=html::encode($this->context->city)?>  -->
    </div>
</div>
