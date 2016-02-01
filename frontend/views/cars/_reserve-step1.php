<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 08.11.2015
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\modules\profile\models\Rentact;

use yii\bootstrap\Alert;
use \frontend\modules\account\Module;
use yii\helpers\Json;

$this->registerCssFile('/css/reserve-a-car.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-reserve-a-car-theme');

$this->registerCssFile('/js/datepicker/datepicker.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-reserve-a-car-theme2');

$this->registerJsFile('/js/reserve-a-car.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/datepicker/datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('/css/magnific.css');
$this->registerJsFile('/js/magnific.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


$rentact = Rentact::find()->asclients()->where(['system_id' =>$systemModel->id])->active()->asArray()->all();
$rentact = Json::encode($rentact);

$errors = $rentModel->getErrors();

if(!empty($errors)){

    $errorSumText = '';
    foreach($errors as $attr => $errorsAttr){
        foreach($errorsAttr as $errorText){
            if(!is_integer($errorText))
                $errorSumText .=$errorText."<br/>";
        }
    }
    if(!empty($errorSumText))
    echo Alert::widget(['options' => ['class' => 'alert-danger', 'style'=>'margin:20px;'], 'body' => $errorSumText]);
}


?>
<h2 style="text-align: center; margin: 10px 0px; font-size: 16px;"><span class="sp-brone">Забронировать авто</span></h2>
<div class="des-calendar-top" style="  padding-left: 123px;">
    <img src="/images/list-auto.jpg" style="position: absolute; left: 341px; height: 27px; top: 0px;">
    <span style="position: absolute; left: 376px; font-size: 13px;">свободен</span>
    <span style="position: absolute; left: 469px; font-size: 13px;">занят</span>
    1. Выберите период аренды

</div>

<!-- календарь -->

<div id="datepickerContainer" style="overflow: hidden; margin: auto"><input type="hidden" id="daterange" class="datarange" name="daterange" value="" style="width:400px;"/></div>

<!-- календарь -->


<div id="panel-contactus">

    <div style="padding-left:30px;">

        <?php
        $classBlock = 'center-form-part';
        $form = ActiveForm::begin(['id'=>'rent-form']);

        ?>
        <?php
        if(Yii::$app->user->isGuest):?>
            <div style="margin-bottom: 10px;text-align: center;padding-left:0px;" class="des-calendar-top">2. Укажите информацию о себе</div>

            <div id="left-form-part" style="clear: both">

                <div class="row-input">
                    <?= $form->field($rentModel, 'name', ['template'=>'{input}'])->textInput(['maxlength' => true, 'placeholder'=> 'Имя']) ?>
                </div>

                <div class="row-input">
                    <?= $form->field($rentModel, 'phone', ['template'=>'{input}'])->textInput(['maxlength' => true,'id' => 'Rentact_phone', 'placeholder' => "+7 (___) ___-__-__"]) ?>
                </div>
            </div>

        <?php
            $classBlock = 'right-form-part';
            endif
        ?>

        <div id="<?=$classBlock?>">
            <div style="height: 45px;margin-top: 5px;" class="row-input">

                <?= $form->field($rentModel, 'days', ['template'=>'{input}{error}'])->hiddenInput(['class'=>'res_brone_date'])->label('Дата не выбрана'); ?>

                <?= $form->field($rentModel, 'rent_from', ['template'=>'{input}<div class="custom-error-block" id="error_rent_period"></div>'])->hiddenInput([])->label('Дата не выбрана'); ?>
                <?= $form->field($rentModel, 'rent_to', ['template'=>'{input}'])->hiddenInput([])->label('Дата окончания брони выбрана'); ?>

                <?//= $form->field($rentModel, 'comment', ['template'=>'{input}'])->textarea(['rows' => 2, 'placeholder'=> 'Комментарий']) ?>

                <?= Html::submitButton('Бронировать', ['id'=>'reserveButton','class' => 'bty']) ?>
            </div>
        </div>

        <div style="clear:both;"></div>
        <?php
            if(isset($errors['registration-error'])){
                Alert::begin([
                    'options' => [
                        'class' => 'alert-warning',
                    ],
                ]);

                if($errors['registration-error'][0] == 2){
                    echo 'Бронирование под профилем автопроката не доступно, пожалуйста выполните &nbsp;<em>'.Html::a('выход',[Module::URL_ROUTE_LOGOUT], ['data-method'=>'post']).'</em> из аккаунта';
                } else {
                    echo 'Указанный Вами номер телефона уже зарегистрирован в системе, пожалуйста выполните&nbsp;<em>'.Html::a('вход',[Module::URL_ROUTE_LOGIN]).'</em>';
                }
                Alert::end();
            }
        ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>
</div>

<?php

/**************calendar********************************/

$jsInit = "var mDate = '".date('Y-m-d H:m:s')."'; ";
$jsInit .= "var maDate = '".date('Y-m-d H:m:s', strtotime("+3 months"))."'; ";

$jsInit .= "var rentedDate = $.parseJSON('".$rentact."'); ";

$jsInit .= "var payType = '".$systemModel->PayType."'; ";
$jsInit .= "var paySumm = '".$systemModel->PaySumm."'; ";

$jsInit .= "var IsClientPayCom = '".$systemModel->IsClientPayCom."'; ";

$jsInit .= "var percents = '".$systemModel->PayPercent."'; ";
$jsInit .= "var cost1 = '".$systemModel->cost1."'; ";
$jsInit .= "var cost2 = '".$systemModel->cost2."'; ";
$jsInit .= "var cost8 = '".$systemModel->cost8."'; ";

$js = <<< JS

$(document).ready(function(){

var dateText = '';

var datarange = jQuery('#daterange');
    var options = {
        locale: {
            "format": 'YYYY-MM-DD H:mm:ss',
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "С",
            "toLabel": "По",
            "customRangeLabel": "Custom",
            "daysOfWeek": ["вс","пн","вт","ср","чт","пт","сб"],
            "monthNames": [
                "Январь",
                "Февраль",
                "Март",
                "Апрель",
                "Май",
                "Июнь",
                "Июль",
                "Август",
                "Сентябрь",
                "Октябрь",
                "Ноябрь",
                "Декабрь"
            ],
            "firstDay": 1
        },
        parentEl: '#datepickerContainer',
        timePicker: false,
        timePicker24Hour: true,
        showDropdowns: false,
        drops: 'down',
        autoApply: false,
        singleDatePicker: false,
        positionFixed: true,
        timePickerIncrement: 60,
        dateLimit: true,
        minDate: mDate,
        maxDate: maDate,
        timeStartHours: 9,
        timeEndHours: 18,
        setReservedDates: rentedDate,
        linkedCalendars: true,
        setPayType: payType,
        setPaySumm: paySumm,

        setPercent: percents,
        setCost1: cost1,
        setCost2: cost2,
        setCost8: cost8,

        IsClientPayCom: IsClientPayCom,

        template:
        '<div class="main daterangepicker ">' +
            '<div class="calendar left">' +
                '<div class="calendar-table"></div>' +
            '</div>' +
            '<div class="calendar right">' +
                '<div class="calendar-table"></div>' +
            '</div>' +
            '<div class="timerangepicker startTimer" data-timertype="startTimer">' +
            '</div>' +
            '<div class="timerangepicker endTimer" data-timertype="endTimer">' +
            '</div>' +
            '<div class="ranges">' +
            '</div>' +
            '<div id="payInfo">' +
            '</div>' +
        '</div>'
    };

    var datepick = datarange.daterangepicker(options/*,
        function(start, end, label) {
            alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        }
        */
    );

    datepick.data('daterangepicker').show();
/**************************/

    var resultDatapick = '';

    $('#reserveButton').on('click', function(){

        $('#error_rent_period').html('');

        resultDatapick = datepick.data('daterangepicker').checkApply();

        if(!resultDatapick.errorText){
           $('#rentact-rent_from').val(resultDatapick.rentFrom);
           $('#rentact-rent_to').val(resultDatapick.rentTo);
        } else {
            $('#error_rent_period').html(resultDatapick.errorText);
            return false;
        }

    });

/*********************************************************/
    function setConfirmFormValue(formObj){

        var name = formObj.find('#rentact-name').val();
        var phone = formObj.find('#rentact-phone').val();
        var comment = formObj.find('#rentact-comment').val();

        var rent_from = formObj.find('#rentact-rent_from').val();
        var rent_to = formObj.find('#rentact-rent_to').val();

        var rentFromMoment = moment(rent_from, "YYYY-MM-DD hh:mm:ss");

        if(rent_to){
            var rentToMoment = moment(rent_to, "YYYY-MM-DD hh:mm:ss");
            var dateText = ' с ' + rentFromMoment.format('hh:mm') +' <b>'+ rentFromMoment.format('DD.MM.YYYY')
                            + '</b> по ' + rentToMoment.format('hh:mm')+' <b>'+rentToMoment.format('DD.MM.YYYY') + '</b>';
        } else {
            var dateText = moment(rent_from, "YYYY-MM-DD hh:mm:ss");
        }

        var confirForm =  $('#rent-confirm-form');


        confirForm.find('#rentact-name-conf').val(name);
        confirForm.find('#rentact-phone-conf').val(phone);
        confirForm.find('#comment_conf').val(comment);

        confirForm.find('#rent_from_conf').val(rent_from);
        confirForm.find('#rent_to_conf').val(rent_to);

        confirForm.find('#reserveDates').text(dateText);

        setPaymentFormValue(dateText);
    }

    function setPaymentFormValue(rentId){
        var formPay = $('#form-to-pay');

        $(formPay).find('#reserveDatesPay').html(dateText);
        $(formPay).find('#paySumm').text(resultDatapick.resPaySumm);

        $(formPay).find("[name='label']").val(rentId);
        $(formPay).find("[name='sum']").val(resultDatapick.resPaySumm);

    }
/*****************************************/

     $('#rent-form').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);

        if(form.find('.has-error').length) {
            return false;
        }

        setConfirmFormValue(form);
        return true;
        /*
        $('#divPaymentData').show(800);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(), // serializes the form's elements.
            success: function(dataRes)
            {
                dataRes=JSON.parse(dataRes);

                datepick.data('daterangepicker').resetSelected();
                datepick.data('daterangepicker').setReservedDates(dataRes);
                datepick.data('daterangepicker').updateView();

                setPaymentFormValue(dataRes.newRentactId);
                $('#divPaymentData').show(800);

                //$('#divDisabled').show(800);
                //$('#divDisabled').hide(800);
            },
            error: function(error) {
                alert('Произошла ошибка');
            }
        });

        return false;
        */
    });


});

JS;

$this->registerJs($jsInit.$js);