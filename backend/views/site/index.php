<?php

use yii\widgets\ListView;
use yii\grid\GridView;
use yii\helpers\html;

/* @var $this yii\web\View */

$this->title = 'Панель управления';
?>
<div class="row site-index">

    <div class="col-xs-6 text-center">
        <h3 style="text-align: center; font-family: 'Open Sans'; font-weight: 300; color: #545454;">Автосалонов</h3>
        <div style=" font-family: 'Chewy'; font-weight: normal; width: 200px; height: 200px; font-size: 78px; margin: auto; line-height: 200px; color: #fff; background-color: #52CD14; border-radius: 50%;">
            <?php
                echo frontend\modules\account\models\Account::getCountAccounts();
            ?>

        </div>
    </div>

    <div class="col-xs-6">
        <div style="float:left;width:320px;text-align:center;">
            <h3 style="  text-align: center;
      font-family: 'Open Sans';
      font-weight: 300;
      color: #545454;">Автомобилей</h3>
            <div style="  font-family: 'Chewy';
      font-weight: normal;
      width: 200px;
      height: 200px;
      font-size: 78px;
      margin: auto;
      line-height: 200px;
      color: #fff;
      background-color: #149DCD;
      border-radius: 50%;">
                <?php
                echo frontend\models\System::getCountSystem();
                ?>
            </div>
        </div>
    </div>

</div>

<div class="row site-index">
    <div class="col-xs-12">
        <?php

        yii\widgets\Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'Account.username',
                    'format' => 'html',
                    'label' => 'Аккаунт',
                    'value' => function ($model) {
                        return $model->account->id;
                    },
                ],
                'category',
                //'cost1',
                // 'cost2',
                // 'cost8',
                'min_cost',
                // 'trans',
                // 'conditioner',
                // 'w_driver',
                // 'pledge',
                // 'info',
                // 'contract',
                // 'photo',
                // 'photos:ntext',
                // 'wheel',
                // 'gear',
                // 'number',
                // 'year',
                // 'power',
                // 'fuel',
                // 'slug_url:url',
                // 'is_display',
                // 'is_aviable',
                // 'is_moderated',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
                    'options' => ['width' => '70']
                ],

//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

        <?/*= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'showHeader' => true,
        'layout' => '{items}',
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user',
                'format' => 'html',
                'label' => 'Авто',
                'value' => function ($model) {
                        return $model->system->name;
                },

            ],

            [
                'attribute' => 'userData.name',
                'format' => 'html',
                'label' => 'Имя',
                'value' => function ($model) {
                    return $model->userData->name;
                },
            ],

            [
                'attribute' => 'userData.phone',
                'format' => 'html',
                'label' => 'Телефон',
                'value' => function ($model) {
                    return $model->userData->phone;
                }
            ],

            [
                'attribute' => 'created_at',

                'label' => 'заявка от',
                'filter' => ''
            ],

            [
                'attribute' => 'rent_from',
                'format' => 'html',
                'label' => 'Период аренды',
                'value' => function ($model) {
                    return 'с ' . date('<b>d.m.Y</b> H:m:s', strtotime($model->rent_from)) . ' по ' .date('<b>d.m.Y</b> H:m:s', strtotime($model->rent_to));
                },
                'filter' => ''
            ],

            [
                'attribute' => 'status',
                'format' => 'html',
                'label' => 'Статус',
                'content'=>function($data){
                    return $data->getStatusLabel($data->status);
                },
                //'filter' => $model::getModerationStatus()
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {confirm}',
                'buttons' => [
                    'delete' => function ($url, $model){
                        return Html::a(
                            '<span class="glyphicon glyphicon-screenshot"></span>',
                            $url);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-screenshot"></span>',
                            $url);
                    },
                ],
                'options' => ['width' => '000']
            ],

            [
                'label' => '',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        'оформить аренду',
                        Url::toRoute(['confirm','id'=>$data->id]),
                        [
                            'title' => 'оформить аренду',
                        ]
                    );
                }
            ],

        ],
    ]); */?>


        <?php yii\widgets\Pjax::end(); ?>

    </div>
</div>