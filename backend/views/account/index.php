<?php

use yii\widgets\ListView;
use yii\grid\GridView;
use yii\helpers\html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Панель управления';
?>
<div class="row site-index">
    <div class="col-xs-12">
        <h1>Модерирование салонов:</h1>
        <?php

        yii\widgets\Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'phone',
                [
                    'attribute' => 'username',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::a($model->username,['/auto/index', 'SystemAutoSearch[account_id]'=>$model->id]);
                    },
                ],

                [
                    'attribute' => 'City.name',
                    'format' => 'html',
                    'label' => 'Город',
                    'value' => function ($model) {
                        if($model->city)
                            return $model->city->name;
                    },
                ],
                'email:email',
                // 'lastLoginAt',
                 "createdAt",
                 'updatedAt',
                // 'status',
                 'balance',
                // 'url:url',
                // 'xy',
                 'address',
                // 'thumb',
                // 'city_name',
                // 'place_delivery',
                // 'other',
                // 'slug_url:url',
                // 'contract:ntext',
                // 'is_moderated',
                // 'is_salon',

                [
                    'filter'=>array("0"=>"Новые", "1"=>"Модерация не пройдена", "2"=>"Модерация пройдена"),
                    'attribute' => 'is_moderated',
                    'format' => 'html',
                    'label' => 'Статус',
                    'value' => function ($model) {
                        return Html::img('/images/is_moderated_'.$model->is_moderated.'.png', ['alt'=> 'Модерация - '.$model->Moderate,'title'=>'Модерация - '.$model->Moderate,]);
                    },
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
                    'options' => ['width' => '60']
                ],
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