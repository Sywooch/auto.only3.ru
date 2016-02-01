<?php


use frontend\modules\account\Module;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use yii\helpers\ArrayHelper;

use kartik\select2\Select2;

use \yii\widgets\MaskedInput;

use frontend\models\City;

use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model nord\yii\account\models\SignupForm */
/* @var $captchaClass yii\captcha\Captcha */

$this->title = Module::t('views', 'Регистрация');

$errors = Yii::$app->session->getFlash('registration-errors');

if($flashErrors = Yii::$app->session->getFlash('registration-errors')){

    $errorText = $flashErrors;
    if(is_array($flashErrors)){
        $errorText = 'Пожалуйста исправьте следующие ошибки:<br/>';
        foreach($flashErrors as $error){
            $errorText .= $error[0];
        }
    }
    echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $errorText]);
}
?>
<div class="register-controller index-action">

    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">

            <h1 class="page-header" style="text-align: left;padding-left: 155px;">Регистрация</h1>

            <p class="help-block">
                <?= Module::t(
                    'views',
                    'Если вы уже зарегистрированы, выполните &mdash; {loginLink}',
                    ['loginLink' => Html::a(Module::t('views', 'Вход'), [Module::URL_ROUTE_LOGIN])]
                ); ?>
            </p>

            <?php $form = ActiveForm::begin([
                'id' => 'signupform',
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'validateOnBlur' => true,
            ]); ?>


            <fieldset>

                <?php

                    $data = ArrayHelper::map(City::find()->orderBy('name')->all(),'name','name');
                    echo $form->field($model, 'city_name')->widget(Select2::classname(), [
                        'data' => $data,
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите город ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10
                        ],
                    ]);

                ?>
                <?= $form->field($model, 'username'); ?>

                <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'id' => 'phone', 'placeholder' => "+7 (___) ___-__-__"]) ?>

                <?/*= $form->field($model, 'phone')->widget(MaskedInput::classname(), [
                    'clientOptions' => [
                        'name' => 'phone',
                        'mask' => '8(999)999-9999',
                        'alias' => 'phone',
                    ]
                ])*/ ?>

                <?= $form->field($model, 'email'); ?>

                <?= $form->field($model, 'url'); ?>

                <?= $form->field($model, 'password')->passwordInput(); ?>
                <?= $form->field($model, 'verifyPassword')->passwordInput(); ?>
                <?php if (Module::getInstance()->enableCaptcha): ?>
                    <?= $form->field($model, 'captcha')->widget($captchaClass, [
                        'captchaAction' => Module::URL_ROUTE_CAPTCHA,
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                        'imageOptions' => ['height' => 35],
                    ]); ?>

                    <p class="help-block">
                        <?= Module::t(
                            'views',
                            'Пожалуйста введите проверочный код'
                        ); ?>
                    </p>
                <?php endif; ?>

                <div class="form-group ">
                    <label class="control-label" for=""></label>
                    <?= Html::submitButton(Module::t('views', 'Зарегистрироваться'), ['class' => 'bt action-button shadow animate blue']); ?>
                </div>


            </fieldset>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
     <div class="row" id="box-oplata">
         <p>
             После регистрации Вам <u>бесплатно</u> будет доступно использование списка неблагонадежных клиентов других авто-прокатов, добавленных в базу auto.only3.ru
         </p>

         <p>Всего за 1 рубль в день мы будем:
         <ul>
             <li>продвигать ваши автомобили через поисковые системы в вашем регионе;</li>
             <li>рекламировать машины на сайте;</li>
             <li>поддерживать систему резервирования и информировать вас о новых заявках.</li>
         </ul>
         <br/>Для возможности аренды Ваших автомобилей со стороны клиентов сайта установлена суточная тарификация.
         Стоимость определяется из расчета: 1 машина = 1 рубль в день. Суточная плата определяется из расчета общего количества машин, зарегистрированных в системе.</p>
    </div>     
</div>

<?php
$js = <<< JS
    $("#phone").mask("+7 (999) 999-99-99");
JS;

$this->registerJs($js);

?>