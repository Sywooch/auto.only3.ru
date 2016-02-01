<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UsersDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список клиентов автопрокатов';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php yii\widgets\Pjax::begin(); ?>

    <?= GridView::widget([
        'emptyText' => 'Список клиентов вашего салона проката пуст',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y <br/>H:i:s']
            ],
            'name',
            'phone',
            [
                'attribute'=>'city.name',
                'label' => 'Город клиента'
            ],
            [
                'attribute' => 'SalonName',
                'format'=>'html',
                'label' => 'Добавил прокат',
                'value'=> function($data){
                    return Html::a(Html::encode($data->SalonName),['account/index','id' => $data->salon->id]);
                }
            ],

            [
                'label' => 'Причина',
                'value' => function($data) {
                    if(empty($data->black_text))
                        return '';

                    return $data->black_text;
                }
            ],
            [
                'label' => '',
                'value' => function($data){
                    if(!$data->rentact){

                    }

                    return '';
                }

            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '60'],
                'buttons'=>[
                    'delete' => function ($url, $model) {
                        if($model->canDelete()){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                            ]);
                        }
                    }
                ]
            ],
            // 'passport_serion',
            // 'birth_date',
            // 'address_reg',
            // 'address_fact',
            // 'license_number',
            // 'license_date',
            // 'image_passport_photo',
            // 'image_passport_reg',
            // 'image_drive_licence',
            // 'inn',
            // 'kpp',
            // 'ogrn',
            // 'okpo',
            // 'r_sch',
            // 'bank',
            // 'k_sch',
            // 'bik',
            // 'director',
            // 'is_confirmed',
            // 'account_id',
            // 'salon_account_id',
            // 'images',
            // 'created_at',
            // 'updated_at',
            // 'document_num',
            // 'passport_give',
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php yii\widgets\Pjax::end(); ?>

</div>
