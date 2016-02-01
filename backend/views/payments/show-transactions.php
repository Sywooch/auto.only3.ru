<?php

use yii\widgets\ListView;
use yii\grid\GridView;
use yii\helpers\html;
use yii\helpers\Url;

$this->registerCssFile('/backend/web/css/show-transactions.css', [
    'depends' => [\backend\assets\AppAsset::className()
    ]
]);

/* @var $this yii\web\View */

$this->title = 'Выплаты автосалонам';

?>

<div class="row site-index">
    <div class="col-xs-12" id="box-list-transaction">

        <div id="box-transaction">
            <h1>Заявки на выплату</h1>

            <ul id="list-tp">
                <li id="lit1">авто</li>
                <li id="lit2">дата платежа</li>
                <li id="lit3">номер операции</li>
                <li id="lit4">реквизиты салона</li>
                <li id="lit5">расчеты</li>
                <li id="lit6">статус</li>
            </ul>


            <?php yii\widgets\Pjax::begin(); ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'options' => ['tag'=>'ul', 'class'=>'items', 'id'=>'list-mss'],
                'itemOptions' => ['class' => 't-item no-pay', 'tag'=>'li'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_itemListTransaction', ['model' => $model]);
                },
            ]) ?>
            <?php yii\widgets\Pjax::end(); ?>

        </div>

    </div><!-- edit-settings -->

</div>