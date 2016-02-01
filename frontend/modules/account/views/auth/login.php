<?php
/*
 * This file is part of Account.
 *
 * (c) 2014 Nord Software
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use frontend\modules\account\Module;

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use \yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Module::t('views', 'Авторизация');

?>
<div class="authenticate-controller login-action">


    <div class="col-md-5 col-md-offset-4">

            <h1 class="page-header">Личный кабинет</h1>

            <?php $form = ActiveForm::begin(['id' => 'loginform']); ?>

            <fieldset>
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'id' => 'phone', 'placeholder' => "+7 (___) ___-__-__"]) ?>

                <?= $form->field($model, 'password')->passwordInput(); ?>
                <div class="form-group">
                   <label></label><?= $form->field($model, 'rememberMe')->checkbox(); ?>
                </div>
                <div class="form-group">
                    <label></label><?= Html::submitButton(Module::t('views', 'Войти'), ['class' => 'bt action-button shadow animate blue']); ?>
                </div>
            </fieldset>

             <?php ActiveForm::end(); ?>

             <div class="box_pass">
                <a href="<?= Module::URL_ROUTE_FORGOT_PASSWORD ?>">Забыли пароль?</a>
                <a style="color: #239910;" href="<?= Module::URL_ROUTE_SIGNUP ?>">Регистрация</a>
            </div>


        </div>

<div style="clear:both;"></div>
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