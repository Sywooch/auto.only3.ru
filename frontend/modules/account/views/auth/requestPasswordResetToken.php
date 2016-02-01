<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста введите номер телефона.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
                'enableClientValidation' => false
            ]); ?>

                <?= $form->field($model, 'phone')->textInput(['id'=>'phone', 'placeholder' => "+7 (___) ___-__-__"]) ?>

                <div style="width: 200px;">
                <?= $form->field($model, 'captcha')->widget(Captcha::className(),[
                    'captchaAction' => '/account/auth/captcha',
                ])->label('Проверочный код') ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('получить пароль', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<< JS

    $(document).ready(function(){

       $("#phone").mask("+7 (999) 999-99-99");
        $("#phone").on('change', function(ev){
            var parEl = $(this).parent();
            if($(parEl).hasClass('has-error')){
                $(parEl).removeClass('has-error');
            }
        });

    });

JS;

$this->registerJs($js);
?>