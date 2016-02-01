<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 08.11.2015
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;

use frontend\modules\account\Module;

?>
<div class="divDisabled" id="divStepData" style="display: block">
    <div class="ReserveInfo" style="display: block">
        <div class="ReserveInfoContent" style="text-align: left;">
        <?php
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

        <br/><br/>Была успешно отправлена, с Вами скоро свяжется наш менеджер.';
            Alert::end();
        ?>

            <div class="alert alert-info">
                Пользователь с номером телефона: <strong><?=$loginModel->phone?></strong>
                <br/>был обнаружен в системе.
                <br/>Для редактирования персональных данных пожалуйста выполните вход;
                    <?php $form = ActiveForm::begin(
                        [   'id' => 'loginform',
                            'action' => Module::URL_ROUTE_LOGIN
                        ]
                    ); ?>
                    <fieldset>
                        <?= $form->field($loginModel, 'phone')->textInput(['maxlength' => true,'id' => 'phone', 'placeholder' => "+7 (___) ___-__-__"]) ?>
                        <?= $form->field($loginModel, 'password')->passwordInput(); ?>
                        <div class="form-group">
                            <label></label><?= $form->field($loginModel, 'rememberMe')->checkbox(); ?>
                        </div>
                        <div class="form-group">
                            <label></label><?= Html::submitButton(Module::t('views', 'Войти'), ['class' => 'bt action-button shadow animate blue']); ?>
                        </div>
                    </fieldset>

                    <?php ActiveForm::end(); ?>
                    <div style="text-align: center">
                        <a href="<?= Module::URL_ROUTE_FORGOT_PASSWORD ?>">Забыли пароль?</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
    $("#phone").mask("+7 (999) 999-99-99");
JS;

$this->registerJs($js);

?>