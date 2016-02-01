<?php
//классы стилей добавлять в frontend/web/css/reserve-a-car.css
//rentModel = модель для бронирования

//$model->photo; -   главная фотка;
//$model->PhotosUrl - массив с урлами других изображений;
//$model->cost1 - стоимость1
//$model->cost2;
//$model->cost8;

//$model->min_cost; - минимальная стоимосьт
//$model->trans; - коробка (1-ручная, 2-авто)
//$model->conditioner; - кондц (Y-есть, N-нет, empty);
//$model->pledge; -  залог;
//$model->info; -  свободная информация;
//$model->contract; -  ( если заполнен - текст контракта, если пусто ссылка на наш);

//$model->w_driver; - с водитем (Y-да, N-нет, empty);

//$model->account->name Название аккаунта
//$model->account->phone телефон
//$model->account->url  - ссылка на сайт
//$model->account->address - адрес салона
//$model->account->balance - баланс

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\MaskedInput;
use yii\widgets\DetailView;

use frontend\components\KirovCalendarClass\KirovCalendarClass;

use frontend\modules\profile\models\Rentact;
use frontend\modules\profile\models\Busy;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
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

$rentact = Rentact::find()->where(['system_id' =>$model->id])->asArray()->all();
$rentact = Json::encode($rentact);

$this->registerCssFile('/css/magnific.css');
$this->registerJsFile('/js/magnific.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>



        
        <div class="row">
      
                <?php if($model->is_aviable):?>
                <div class="row">
                    <div class="col-xs-12">

                        <div style="position: relative;">
                            <div id="divDisabled">
                                <div class="ReserveInfo">
                                <div class="ReserveInfoContent">
                                    <?php $form = ActiveForm::begin(['id'=>'rent-confirm-form']); ?>

                                    <h2 class="text-center">Подтвердите бронь</h2>
                                    <div class="ReserveInfoDates">
                                        <span>Дата:</span><span id="reserveDates"></span>
                                    </div>
                                        <div class="row-form">
                                           <?= $form->field($rentModel, 'name', [])->textInput(['id'=>'rentact-name-conf','maxlength' => true, 'placeholder'=> '']) ?>
                                        </div>

                                        <div class="row-form">

                                            <?= $form->field($rentModel, 'phone', [

                                                ])->widget(MaskedInput::classname(), [
                                                'clientOptions' => [
                                                    'name' => 'phone',
                                                    'mask' => '8(999)999-9999',
                                                    'alias' => 'phone',
                                                ]
                                            ])->textInput(['id'=>'rentact-phone-conf']) ?>

                                        </div>

                                        <?= $form->field($rentModel, 'comment', ['template'=>'{input}'])->hiddenInput(['id'=>'comment_conf']); ?>
                                        <?= $form->field($rentModel, 'rent_from', ['template'=>'{input}'])->hiddenInput(['id'=>'rent_from_conf']); ?>
                                        <?= $form->field($rentModel, 'rent_to', ['template'=>'{input}'])->hiddenInput(['id'=>'rent_to_conf']); ?>

                                        <div class="row-form passport">
                                           <?= $form->field($rentModel, 'passport_serion', [])->textInput(['maxlength' => true, 'placeholder'=> '']) ?>
                                           <div class="passport_seria">
                                               <?= $form->field($rentModel, 'passport_number', [])->textInput(['maxlength' => true, 'placeholder'=> '']); ?>
                                           </div>
                                        </div>

                                        <div class="row-form" style="clear: both">
                                            <?= $form->field($rentModel, 'license_number', [])->textInput(['id'=>'rentact-phone-conf','maxlength' => true, 'placeholder'=> '']) ?>
                                        </div>

                                        <div class="row-form row-submit">
                                            <span onclick="$('#divDisabled').hide(800);" id="del-success">Отменить</span>
                                            <input type="submit" value="Подтвердить" id="add-success">
                                        </div>
                                    <?php ActiveForm::end()?>
                                    </div>
                                </div>
                            </div>
                           <!-- <h2 style="text-align: center; margin: 10px 0px; font-size: 16px;"><span class="sp-brone">Забронировать авто</span></h2> -->
                            <div class="des-calendar-top" style="padding-left: 85px;">
                                <img src="/images/list-auto.jpg" style="position: absolute; left: 435px;height: 27px; top: 0px;">
                                <span style="position: absolute; left: 469px; font-size: 13px;">свободен</span>
                                <span style="position: absolute; left: 564px; font-size: 13px;">занят</span>
                                1. Выберите дату начала и окончания аренды

                            </div>

                            <!-- календарь -->

                            <div id="datepickerContainer" style="overflow: hidden; margin: auto"><input type="hidden" id="daterange" class="datarange" name="daterange" value="" style="width:400px;"/></div>

                            <!-- календарь -->


                            <div id="panel-contactus">

                                <div style="padding-left:30px;">

                                    <?php

                                    $form = ActiveForm::begin(['id'=>'rent-form']); ?>
                                        <div style="margin-bottom: 10px;text-align: center;padding-left:0px;" class="des-calendar-top">2. Укажите информацию о себе</div>

                                        <div id="left-form-part" style="margin-left: 45px;">

                                            <div class="row-input">
                                                <?= $form->field($rentModel, 'name', ['template'=>'{input}'])->textInput(['maxlength' => true, 'placeholder'=> 'Имя/ФИО']) ?>
                                            </div>

                                            <div class="row-input">

                                                <?= $form->field($rentModel, 'phone', [

                                                    'template'=>'{input}'])->widget(MaskedInput::classname(), [

                                                    'clientOptions' => [
                                                        'name' => 'phone',
                                                        'mask' => '8(999)999-9999',
                                                        'alias' => 'phone',
                                                    ]
                                                ], ['placeholder'=>'Телефон']) ?>

                                            </div>

                                            <? /*
                                            <div class="row-input">
                                                <?= $form->field($rentModel, 'email', ['template'=>'{input}'])->textInput(['maxlength' => true, 'placeholder'=> 'Укажите почту для напоминания']) ?>
                                            </div>
                                            */ ?>
                                        </div>


                                        <div id="right-form-part">

                                            <div class="row-input">
                                                <?= $form->field($rentModel, 'days', ['template'=>'{input}{error}'])->hiddenInput(['class'=>'res_brone_date'])->label('Дата не выбрана'); ?>

                                                <?= $form->field($rentModel, 'rent_from', ['template'=>'{input}<div class="custom-error-block" id="error_rent_period"></div>'])->hiddenInput([])->label('Дата не выбрана'); ?>
                                                <?= $form->field($rentModel, 'rent_to', ['template'=>'{input}'])->hiddenInput([])->label('Дата окончания брони выбрана'); ?>

                                                <?//= $form->field($rentModel, 'comment', ['template'=>'{input}'])->textarea(['rows' => 2, 'placeholder'=> 'Комментарий']) ?>
                                            </div>

                                            <div style="height: 45px;margin-top: 5px;" class="row-input">

                                                <?= Html::submitButton('Бронировать', ['id'=>'reserveButton','class' => 'bty', 'data-pjax' => '1', 'style'=>"width: 240px;margin-top: -3px;"]) ?>

                                            </div>

                                        </div>
                                        <div style="clear:both;"></div>

                                    <?php ActiveForm::end(); ?>

                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <?php endif?>


         </div>


<?php

$jsInit = "var mDate = '".date('Y-m-d H:m:s')."'; ";
$jsInit .= "var maDate = '".date('Y-m-d H:m:s', strtotime("+3 months"))."'; ";

$jsInit .= "var rentedDate = $.parseJSON('".$rentact."'); ";

$js = <<< JS


$('#del-success').on('click', function(){
     $('#divDisabled').hide(800);
     return false;
});

$('#add-success_old').on('click', function(e){

    e.preventDefault();
    var form = $('#rent-form');

    $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(), // serializes the form's elements.
            success: function(dataRes)
            {
                dataRes=JSON.parse(dataRes);

                $.each(dataRes, function( ind, val ) {
                    if(val.day){
                        var f = $('*[data-datestr="'+val.day+'"]');
                        var d = f.parent();
                        d.removeClass('cell_nobrone');
                        d.removeClass('cell_active');
                        d.addClass('cell_brone');
                    }
                });
                $('.res_brone_date').val('');
                $('#divDisabled').hide(800);
                yaCounter32280994.reachGoal('BRONE'); return true;  
            },
            error: function(error) {
                alert('Произошла ошибка');
            }
    });
});

$('#left-boxer').magnificPopup({
  delegate: 'a',
  type: 'image',
   gallery: {
    enabled: true
  },
});

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
                "Сентябрь"
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
        '</div>'
    };

    var datepick = datarange.daterangepicker(options/*,
        function(start, end, label) {
            alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        }
        */
    );

    datepick.data('daterangepicker').show();

    var resultDatapick = '';

    $('#reserveButton').on('click', function(){

        $('#error_rent_period').html('');

        resultDatapick = datepick.data('daterangepicker').checkApply();
        if(!resultDatapick.errorText){
           $('#rentact-rent_from').val(resultDatapick.rentFrom);
           $('#rentact-rent_to').val(resultDatapick.rentTo);
        } else {
            $('#error_rent_period').html(resultDatapick.errorText);
        }
    });

/*********************************************************/

    $('#rent-confirm-form').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);
        if(form.find('.has-error').length) {
            return false;
        }

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

                $('#divDisabled').hide(800);
            },
            error: function(error) {
                alert('Произошла ошибка');
            }
        });

        return false;
    });


JS;

$this->registerJs($jsInit.$js);
?>
