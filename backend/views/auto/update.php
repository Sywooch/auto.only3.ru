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

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Список автомобилей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if($flash = Yii::$app->session->getFlash('success')){
     Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]);
}

?>
<div class="system-auto-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
    foreach($model->PhotosUrl as $photoUrl){
        $photos .= html::img($model->getThumbImage($photoUrl, 210, 158), ['width' => '210px', 'style'=>'margin:5px;']);
    }

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

    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'account.userlogin',
                'format' => 'html',
                'label' => 'Автосалон',
                'value' => $model->account->username,
            ],

            'name',
            'year',
            'cost1',
            'cost2',
            'cost8',
            'min_cost',
            'pledge',
            'info',
            'contract',
            [
                'attribute' => 'photo:',
                'format' => 'html',
                'label' => 'Главное изображение',
                'value' => html::img($model->getThumbImage($model->photo, 210, 158), ['width' => '210px']),
            ],

            [
                'attribute' => 'photos',
                'format' => 'html',
                'label' => 'Изображения',
                'value' => $photos,
            ],
            'power',
            //'slug_url:url',
            'created_at',
            [
                'attribute' => 'is_display',
                'format' => 'html',
                'label' => 'Отображается в списке?',
                'value' => $model->is_display == 1 ? 'Да' : 'Нет'
            ],

            [
                'attribute' => 'is_aviable',
                'format' => 'html',
                'label' => 'Доступна для бронирования?',
                'value' => $model->is_aviable == 1 ? 'Да' : 'Нет'
            ],
            'updated_at',
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
