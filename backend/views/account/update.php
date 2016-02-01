<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */
/* @var $model frontend\modules\profile\models\SystemAuto */
$this->registerCss('
.detail-view th{
 width: 350px;
');

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Список автомобилей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if($flash = Yii::$app->session->getFlash('success')){
     Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]);
}


?>
<div class="system-auto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $lastModerateAr = $model->getLastModeratedValue();
    $resModeratedText = '';
    if(!empty($lastModerateAr)){
        foreach($lastModerateAr as $attr => $valAr){
            $flash = 'Изменено свойство: ' . $valAr['label'] . '<br/>Предыдущее значение: ' . $valAr['old_val'].'<br/>Новое значние: ' .$valAr['new_val']. '<br/>';
            echo Alert::widget(['options' => ['class' => 'alert-info'], 'body' => $flash]);
        }
    }
    ?>

    <p>
        <?//= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить запись', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php

    $form = ActiveForm::begin(['options' => [
        'action' => 'profile/system/create',
        'enctype' => 'multipart/form-data',
        'enableClientValidation' => false,
        'validateOnSubmit' => false,
        'validateOnBlur' => false,

    ]]);

    $photos = '';

    $is_moderated = "Текущий статус - ".$model->Moderate.' '.Html::img('/images/is_moderated_'.$model->is_moderated.'.png', ['alt'=> 'Модерация - '.$model->Moderate,'title'=>'Модерация - '.$model->Moderate,]);

    $is_moderated = $form->field($model, 'is_moderated')->dropDownList($model->getModerateList(), ['id'=>'sel_is_moderated','style'=>'width:150px;'])->label($is_moderated);

    echo "<table class='table table-striped table-bordered detail-view'>
                <tr>
                    <td>
                    ".$is_moderated ."
                    <div id='area_moderated_text'>".$form->field($model, 'moderated_text')->textarea(['rows' => 3, 'cols'=> 63,'class' => '', 'style'=>'display:block'])->label('Что нужно исправить:<br/>')."</div>
                    ".Html::submitButton('Сохранить и сообщить', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary'])."
                    </td>
                </tr>
            </table>";
    ?>


    <?php ActiveForm::end(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'phone',
            'username',
            'city_name',
            'email:email',
            'lastLoginAt',
            'createdAt',
            'updatedAt',
            'balance',
            'address',
            'place_delivery',
            'other',
//            'slug_url',
//            'contract:ntext',
        ],
    ]) ?>

</div>

<?php

$js = <<< JS
    var sel  = $('#sel_is_moderated');
    var text = $('#area_moderated_text');

    if(sel.val()=='2'){
        text.hide();
    }
    $(sel).on('change', function(el){
        text.hide();
        if(sel.val() != '2'){
            text.show();
        }
    })
JS;

$this->registerJs($js);
