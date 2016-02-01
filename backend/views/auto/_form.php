<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\file\FileInput;
use yii\web\View;

use \yii\widgets\MaskedInput;

$this->registerCssFile('/css/profile/system-form.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'position' => View::POS_END
]);

/* @var $this yii\web\View */
/* @var $model frontend\modules\profile\models\SystemAuto */
/* @var $form yii\widgets\ActiveForm */


if(!empty($model->photo)) {
    $prluginOptions = [
        'showCaption' => false,
        'showRemove' => false,
        'showUpload' => false,
        'browseClass' => 'btn btn-primary btn-block',
        'browseLabel' =>  'Загрузить фото',
        'previewFileType' => 'any',
        'initialPreview'=>[
            Html::img($model->photo, ['class'=>'file-preview-image']),
        ],
    ];
} else {
    $prluginOptions = [
        'showCaption' => false,
        'showRemove' => false,
        'showUpload' => false,
        'browseClass' => 'btn btn-primary btn-block',
        'browseLabel' =>  'Загрузить фото',
        'previewFileType' => 'any',
    ];
}

?>

<?php $form = ActiveForm::begin(['options' => [
    'action' => 'profile/system/create',
    'enctype' => 'multipart/form-data',
    'enableClientValidation' => false,
    'validateOnSubmit' => false,
    'validateOnBlur' => false,

]]); ?>

<div class="system-auto-form">

<?= $form->errorSummary($model); ?>

    <div class="row">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder'=>'Пример Audi A6']) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'category')->dropDownList($model->getCategoryList());?>
    </div>

    <div class="row">
        <?= $form->field($model, 'info')->textarea(['rows' => 3,'class' => 'inptxt']) ?>
    </div>

    <div class="row arend">
        <div class="ab_reg">
            Стоимость аренды.
            <p style="  font-size: 13px;
  text-align: center;
  color: #9E9E9E;
  font-family: 'Pt Sans Caption';">Укажите цену за сутки в рублях в зависимости от длительности аренды. <br>Обязательно указать хотя бы отдно поле.</p>
        </div>

        <div style="float:left;width:220px;margin-right:10px;text-align: center;margin-left: 37px;">
            <?= $form->field($model, 'cost1',[])->textInput(['maxlength' => true, 'class' => 'inptxt inp-rub']) ?>
        </div>
        <div style="float:left;width:220px;margin-right:10px;text-align: center;">
            <?= $form->field($model, 'cost2',[])->textInput(['maxlength' => true, 'class' => 'inptxt inp-rub']) ?>
        </div>
        <div style="float:left;width:220px;margin-right:10px;text-align: center;">
            <?= $form->field($model, 'cost8',[])->textInput(['maxlength' => true, 'class' => 'inptxt inp-rub']) ?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="row tech-char">
        <div class="ab_reg">
            Технические характеристики

        </div>

        <?= $form->field($model, 'year')->widget(MaskedInput::classname(), [
            'mask' => '9{1,4}',
            'clientOptions' => [
                'greedy' => true,
                'alias' => 'year'
            ]
        ])->textInput(['maxlength' => true, 'class' => 'inptxt auto-width']); ?>

        <?= $form->field($model, 'power')->widget(MaskedInput::classname(), [
            'mask' => '9{1,3}',
            'clientOptions' => [
                'greedy' => true,
                'alias' => 'power',
            ]
        ])->textInput(['maxlength' => true, 'class' => 'inptxt auto-width']); ?>

        <?= $form->field($model, 'trans')->radioList($model->getTransList(), ['default'=>'1']);?>
    </div>

    <div class="row tech-char">

        <?= $form->field($model, 'conditioner')->radioList([ '0' => 'Отсутствует', '1' => 'Есть', ]) ?>
    </div>

    <div class="row tech-char">
        <div class="ab_reg">
            Условия проката автомобиля
        </div>
        <?= $form->field($model, 'pledge')->textInput(['maxlength' => true, 'class' => 'inptxt inp-rub']) ?>
    </div>

    <div class="row tech-char">
        <?= $form->field($model, 'w_driver')->radioList([ '0' => 'Без водителя', '1' => 'С водителем', ]) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'contract')->textarea(['rows' => 3,'class' => 'inptxt','maxlength' => true])->label(
            'Особые условия <p style="font-size: 13px; color: #9E9E9E; font-family: \'Pt Sans Caption\';  text-align: right;">Если оставляете пустым,<br> то принимаете стандартный <br><a style="color: #448BB1;" href="#">договор о прокате</a></p>'
        ); ?>

    </div>

    <div class="row">
        <div class="ab_reg">
            Фотоописание
            <p style="  margin: 0;
      line-height: 20px;
      font-size: 13px;
      color: #555;">Разрешеннные форматы: <b>JPG</b>,<b>JPEG</b>. Максимальный размер: <b>3Mb</b></p>
        </div>
    </div>

    <div class="row text-center" style="width:290px; margin: auto">
            <?php

            echo $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' =>$prluginOptions,
                'pluginEvents' => [
                    "change" => 'function(event, key) {
    //                      $("#systemauto-photo").val("empty");
                        }'
                ],
            ])->label('Основная фотография');
        ?>
    </div>
    <div class="row center" style="width:760px;">
        <?php
        $form->field($model, 'photos')->hiddenInput(['template' => '{input}']);

        echo html::tag('br');
/*
        echo $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
            'pluginOptions' => $prluginOptionsPhotos,
        ]);
*/
        ?>
    </div>


</div>

<?


$initialPreview = [];
$initialPreviewConfig = [];

if(!empty($model->PhotosUrl)) {
    $resImg = [];
    foreach($model->PhotosUrl as $key => $photoUrl){
        $initialPreview[] = Html::img($photoUrl, ['class'=>'file-preview-image']);
        $initialPreviewConfig[] = [
            'url' => Url::toRoute(['/profile/system/del-image', 'id' => $model->id, 'photoKey' => $key]), // server delete action
            'key' => $key,
            'extra' => '{id: '. $key .'}'
        ];

    }
}

echo $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
    'options' => ['multiple' => true],
    'pluginOptions' => [
        'showUpload' => false,
        'overwriteInitial' => false,
        'previewFileType' => 'image',
        'uploadUrl' => Url::to(['/site/file-upload']),
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig,
    ],
]);

?>

<div class="row tech-char">
    <div class="ab_reg">
        Настройка доступа
    </div>
</div>

<div class="row tech-char">
    <?= $form->field($model, 'is_display')->radioList(['1' => 'Отображать', '0' => 'Не отображать' ]) ?>
</div>

<div class="row tech-char">
    <?= $form->field($model, 'is_aviable')->radioList(['1' => 'Доступна', '0' => 'Не доступна' ]) ?>
</div>


    <div class="row text-center">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btt' : 'btt']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>