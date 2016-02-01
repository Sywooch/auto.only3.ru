<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 08.11.2015
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\web\View;
use yii\helpers\ArrayHelper;

use yii\bootstrap\Alert;
use kartik\file\FileInput;
use frontend\modules\profile\models\SystemAuto;

use frontend\modules\account\Module;

?>
<noindex>
<div class="divDisabled" id="divStepData" style="display: block">
    <div class="ReserveInfo" style="display: block">
        <div class="ReserveInfoContent">
            <?php
                if(Yii::$app->session->get('registered_now')){

                    Alert::begin([
                        'options' => [
                            'class' => 'alert-info',
                            'style' => 'text-align:left;'
                        ],
                    ]);
                ?>
                <?php
                        echo '<strong>Вниманием!</strong> Вы были зарегистрированы в системе auto.only3.ru';
                        echo '<br/><br/>Для входа используйте номер телефона: <strong>'.Yii::$app->user->identity->phone.'</strong>';
                        echo '<br/>Установленный пароль: <strong>'.Yii::$app->session->get('registered_password').'</strong><br/>';

                        $passwordForm = ActiveForm::begin([
                            'id'=>'reset-password-step'
                            //'action' => Module::URL_ROUTE_CHANGE_PASSWORD,
                        ]);
                        ?>
                    <div style="width: 180px; margin: auto;">

                        <?= $passwordForm->field($passwordFormModel, 'password')->passwordInput(['maxlength' => true])->label('Новый пароль')?>
                        <?= $passwordForm->field($passwordFormModel, 'password_confirm')->passwordInput(['maxlength' => true])->label('пароль еще раз') ?>

                        <div class="form-group">
                            <?= Html::submitButton('изменить', ['class' => 'btn-save']) ?>
                        </div>
                    </div>
            <?php
                    $passwordForm::end();

                    Alert::end();
                }
            ?>
    <?php

    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'validateOnSubmit' => false,
        'validateOnBlur' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
        ]
    ]);
        Alert::begin([
            'options' => [
                'class' => 'alert-info',
            ],
        ]);

        echo 'Ваша заявка на аренду:
        <br/>Автомобиль: <strong>'. $systemModel->name .',' .$systemModel->year.'</strong>
        <br/>Период аренды:
            с <strong>'.date("d.m.Y H:i",strtotime($rentModel->rent_from)).'</strong>
            по <strong>'.date("d.m.Y H:i",strtotime($rentModel->rent_to)).'</strong>
        <br/>Телефон: <strong>'.$prepareModel->phone.'</strong>

        <br/><br/>Была успешно отправлена, с Вами скоро свяжется наш менеджер.
        <br/>В случае ошибки Вы можете '.Html::a("отменить аренду", ["cars/cancel-reserve", "id"=>$rentModel->id],["data-method" => "post", "data-confirm" => "Вы действительно хотите отменить аренду?"]).'
        <br/><br/>Для ускорения оформления аренды автопрокатом, заполните форму и загрузите фотографии документов:';
        Alert::end();
    ?>

    <?php
    echo Html::beginTag('div',['class' => 'photosBox']);
    $prluginOptions = [
//    'uploadUrl' => Url::to(['/profile/rentact/file-upload', 'id'=>$userData->id]),
        'showCaption' => false,
        'showRemove' => false,
        'showUpload' => false,
        'browseClass' => 'btn btn-primary btn-block',
        'browseLabel' =>  'Загрузить',
        'previewFileType' => 'any',
    ];

    $preview = ['initialPreview'=> !empty($prepareModel->image_passport_photo) ? Html::img($prepareModel->getPhotoImage('image_passport_photo'), ['class'=>'file-preview-image']) : "" ];
    echo $form->field($prepareModel, 'image_passport_photo')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => array_merge($prluginOptions, $preview),
        'pluginEvents' => [
            "change" => 'function(event, key) {
          $("#systemauto-photo").val("empty");
    }'
        ],
    ]);

    echo Html::tag('hr');
    $preview = ['initialPreview'=> !empty($prepareModel->image_passport_reg) ? Html::img($prepareModel->getPhotoImage('image_passport_reg'), ['class'=>'file-preview-image']) : "" ];
    echo $form->field($prepareModel, 'image_passport_reg')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => array_merge($prluginOptions, $preview),
        'pluginEvents' => [
            "change" => 'function(event, key) {
          $("#systemauto-photo").val("empty");
    }'
        ],
    ]);

    echo Html::tag('hr');
    $preview = ['initialPreview'=> !empty($prepareModel->image_drive_licence) ? Html::img($prepareModel->getPhotoImage('image_drive_licence'), ['class'=>'file-preview-image']) : "" ];
    echo $form->field($prepareModel, 'image_drive_licence')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => array_merge($prluginOptions, $preview),
        'pluginEvents' => [
            "change" => 'function(event, key) {
          $("#systemauto-photo").val("empty");
    }'
        ],
    ]);

    echo Html::endTag('div');

    echo $form->field($prepareModel, 'image_passport_photo',['template'=>'{input}'])->hiddenInput();
    echo $form->field($prepareModel, 'image_passport_photo',['template'=>'{input}'])->hiddenInput();
    echo $form->field($prepareModel, 'image_drive_licence',['template'=>'{input}'])->hiddenInput();

    ?>

    <?//$form->field($prepareModel, 'file')->fileInput() ?>


    <div id="user-box" style="margin-top: 20px;">
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'is_organization')->radioList(['0'=>'Физ. лицо', '1' => 'Юр. лицо'], ['class'=>'is_ortanization_block', 'id'=>'prepare-is_organization']) ?>
        </div>
    </div>

    <div class="jur">
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'comp_name', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'id'=>'comp_name', 'placeholder' => 'АвтоТрейд']) ?>
        </div>

        <div class="step-row-form">
            <?= $form->field($prepareModel, 'opf', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'placeholder' => 'ООО']); ?>
        </div>

    </div>

    <div class="fiz">
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'last_name', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'id'=>'last_name', 'placeholder' => 'Петров']) ?>
        </div>
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'first_name', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'id'=>'first_name', 'placeholder' => 'Вениамин']) ?>
        </div>
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'patronymic_name', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'id'=>'patronymic_name', 'placeholder' => 'Иванович']) ?>
        </div>
    </div>

    <div id="adress-box">
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'address_reg', ['template'=>'{label}{input}'])->textInput(['maxlength' => true])->error(); ?>
        </div>
        <div class="step-row-form">
            <?= $form->field($prepareModel, 'address_fact', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
        </div>
    </div>

    <div class="fiz">
        <div id="passport-box">

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'birth_date', ['template'=>'{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '99.99.9999',
                    'value' => '12.01.2005'
                ]); ?>
            </div>

            <div class="step-row-form passport">
                <?= $form->field($prepareModel, 'passport_serion', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'id' => 'pass-seria']) ?>
                <?= $form->field($prepareModel, 'passport_number', ['template'=>'{label}{input}'])->textInput(['maxlength' => true, 'id' => 'pass-num'])->label('№', ['id'=>'pass-lnum']) ?>
            </div>

        </div>

        <div id="driver-box">

            <div class="step-row-form">
                <?=$form->field($prepareModel, 'license_number', ['template'=>'{label}{input}'])->textInput() ?>
            </div>
            <div class="step-row-form">
                <?= $form->field($prepareModel, 'experience', ['template'=>'{label}{input}'])->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '9{1,2}',
                    'options'=> [
                    'placeholder' => '_']
                ]) ?>
            </div>
        </div>
    </div>

    <div class="jur">

        <div id="jur-box">

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'inn', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'kpp', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'ogrn', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'okpo', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'r_sch', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'bank', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'k_sch', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'bik', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>

            <div class="step-row-form">
                <?= $form->field($prepareModel, 'director', ['template'=>'{label}{input}'])->textInput(['maxlength' => true]); ?>
            </div>
        </div>
    </div>

            <div class="row-form row-submit">
                <input type="submit" value="Отправить данные" id="add-success">
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
    <?php

    $js = <<< JS

$(document).ready(function(){

    var checkedEl = $("#prepare-is_organization input:checked");

    if(checkedEl.val() == 0){
        $('.fiz').show();
        $('.jur').hide();
    } else {
        $('.jur').show();
        $('.fiz').hide();
    }

    $('#prepare-is_organization input[type=radio]').on('click', function(ev){

        if($(this).val() == 0){
            $('.fiz').show();
            $('.jur').hide();
        } else {
            $('.jur').show();
            $('.fiz').hide();
        }

    });

});

JS;

    $this->registerJs($js);
    ?>

</div>
</noindex>