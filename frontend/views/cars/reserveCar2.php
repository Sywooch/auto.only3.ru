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

use app\components\KirovCalendarClass\KirovCalendarClass;

use app\modules\profile\models\Rentact;
use app\modules\profile\models\Busy;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->registerCssFile('/css/reserve-a-car.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-reserve-a-car-theme');

$this->registerJsFile('/js/reserve-a-car.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('/css/magnific.css');
$this->registerJsFile('/js/magnific.js', ['depends' => [\yii\web\JqueryAsset::className()]]);



$allday = array();
$actives = array();

$rentact = Rentact::find()->where(['system_id' =>$model->id])->all();
foreach($rentact as $itm)
{
    $allday[] = $itm->day;
}

$beetween = array();

$cur_m_n = date('n');  $cur_m_n = intval($cur_m_n); // текущий месяц
$cur_y_n = date('Y');  $cur_y_n = intval($cur_y_n); // текущий год

$worker_m = array();
$worker_y = array();
$prev_m = 0;
$prev_y = 0;

//$n_month = $system->calendar;
$n_month = 2;

for($im = 1; $im <= $n_month ; $im++){

    if($im==1)  {
        $worker_m[] = $cur_m_n;
        $worker_y[] = $cur_y_n;
        $prev_m = $cur_m_n;
        $prev_y = $cur_y_n;
    }
    else{

        if($prev_m != 12) {
            $worker_m[] = $prev_m + 1;
            $worker_y[] = $prev_y;
            $prev_m = $prev_m + 1;
        }
        else {
            $worker_m[] = 1;
            $worker_y[] = $prev_y + 1;
            $prev_m = 1;
            $prev_y = $prev_y + 1;
        }
    }

}

?>

<div class="system-auto-view">
    <div class="autoItemView container">

      



        <div class="row">
            <div class="col-xs-3" id="left-boxer">

                <ul id="list-photo-des">
                    <?php
                        echo '<li><a class="gallery" href="'.$model->getThumbImage($model->photo, 1000, 1000).'">';
                            echo html::img($model->getThumbImage($model->photo, 210, 158), ['width' => '210px']);
                        echo '</a></li>';
                    ?>

                    <?php
                        foreach($model->PhotosUrl as $photoUrl){
                            echo '<li><a class="gallery" href="'.$photoUrl.'">';
                                echo html::img($model->getThumbImage($photoUrl, 210, 158), ['width' => '210px']);
                            echo '</a></li>';
                        }
                    ?>
                </ul>
            </div>

            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="phone-img">
                    <span id="number-phone-img" class="phone-service">+7<?=$model->account->phone?></span>
                        </div>

                        <div class="mm-name"><?=html::encode($model->name)?><?=($model->year ? ", ".$model->year:"")?></div>
                        <div class="mm-cat"><?=$model->getCategoryList($model->category);?></div>
                        <p class="mm-other"><?=html::encode($model->info)?></p>

                        <div class="shadow2" style="padding-bottom: 5px;">
                            <div class="shadow2-1">
                                <span>Цены</span>
                                <span style="margin-left: 18px; display: inline-block; text-align: center; width: 200px;">Характеристики</span>
                                <span style="display: inline-block;text-align: center; width: 200px;">Условия</span>
                            </div>
                            <div style="clear:both;"></div>

                            <div style="float:left;width:210px;" class="it-33">
                                <div class="vls">
                                    <?=$model->cost1 ? "<span>".$model->cost1." руб</span> &nbsp;&nbsp;&nbsp;на 1 сутки":"";?>
                                </div>
                                <div class="vls">
                                    <?=$model->cost2 ? "<span>".$model->cost2." руб</span> &nbsp;&nbsp;&nbsp;от 2 суток":"";?>
                                </div>
                                <div class="vls">
                                    <?=$model->cost8 ? "<span>".$model->cost8." руб</span> &nbsp;&nbsp;&nbsp;от 8 суток":"";?>
                                </div>
                            </div>


                            <div style="float:left;width:205px;">
                                <div class="val-param"><span class="vals">Мощность:</span> <span class="names"><?=$model->power?>(л/с)</span></div>
                                <div class="val-param"><span class="vals">Коробка:</span> <span class="names"><?=$model->trans == '1' ? "МКПП": "АКПП"?></span></div>
                                <div class="val-param"><span class="vals">Кондиционер:</span> <span class="names"><?=$model->w_driver == 'Y' ? "нет": "есть"?></span></div>
                            </div>
                            <div style="float:left;width:230px;">

                                <div class="val-param"><span class="vals">Залог:</span> <span class="names"><?=$model->pledge?> Р</span></div>
                                <div class="val-param"><span class="vals">Предоставляется:</span> <span class="names"><?=$model->w_driver == 'Y' ? "с водителем": "без водителя"?></span></div>

                            </div>
                            <div style="clear:both;"></div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">

                        <div class="shadow2" style="position: relative;">
                            <div id="divDisabled">

                                <div class="ReserveInfo">
                                    <div class="ReserveInfoContent">
                                        Подтвердите бронь<br/>
                                        <div class="ReserveInfoDates">
                                            <span>Даты:</span><span id="reserveDates"></span>
                                        </div>
                                        <span id="del-success" onclick="$('#divDisabled').hide(800);">Отменить</span>
                                        <span id="add-success">Подтвердить</span>
                                    </div>
                                </div>
                            </div>
                            <h2 style="text-align: center; margin: 10px 0px; font-size: 16px;"><span class="sp-brone">Забронировать авто</span></h2>
                            <div class="des-calendar-top" style="  padding-left: 123px;">
                                <img src="/images/list-auto.jpg" style="position: absolute; left: 341px; height: 27px; top: 0px;">
                                <span style="position: absolute; left: 376px; font-size: 13px;">свободен</span>
                                <span style="position: absolute; left: 469px; font-size: 13px;">занят</span>
                                1. Выберите свободный день

                            </div>

                            <!-- календарь -->

                            <div>

                                <ul id="list-calendar-month">
                                    <?php
                                    foreach($worker_m as $key=>$w_m){
                                        $g = $key+1;
                                        echo "<li style='margin-right: 20px;'>";
                                            KirovCalendarClass::getCalendar($worker_m[$key], $worker_y[$key], $allday, $actives,array(),$beetween);
                                        echo "</li>";
                                    }
                                    ?>
                                </ul>

                            </div>

                            <!-- календарь -->


                            <div id="panel-contactus">

                                <div style="padding-left:30px;">

                                    <?php Pjax::begin(['id' => 'new_rent']) ?>

                                    <?php

                                    $form = ActiveForm::begin(['id'=>'rent-form']); ?>
                                        <div style="margin-bottom: 10px;text-align: center;padding-left:0px;" class="des-calendar-top">2. Укажите информацию о себе</div>

                                        <div id="left-form-part">

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
                                                ]) ?>

                                            </div>

                                            <div class="row-input">
                                                <?= $form->field($rentModel, 'email', ['template'=>'{input}'])->textInput(['maxlength' => true, 'placeholder'=> 'Укажите почту для напоминания']) ?>
                                            </div>

                                        </div>


                                        <div id="right-form-part">

                                            <div class="row-input">
                                                <?= $form->field($rentModel, 'days', ['template'=>'{input}{error}'])->hiddenInput(['class'=>'res_brone_date'])->label('Дата не выбрана'); ?>
                                                <?= $form->field($rentModel, 'comment', ['template'=>'{input}'])->textarea(['rows' => 2, 'placeholder'=> 'Комментарий']) ?>
                                            </div>

                                            <div style="height: 45px;margin-top: 5px;" class="row-input">

                                                <?= Html::submitButton('Бронировать', ['class' => 'bty', 'data-pjax' => '1']) ?>

                                            </div>

                                        </div>
                                        <div style="clear:both;"></div>

                                    <?php ActiveForm::end(); ?>
                                    <?php Pjax::end();?>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div class="shadow2">
                            <h2 style="margin: 10px 0px;font-size: 16px;"><span class="sp-map">Как к нам добраться</span></h2>

                            <div style="height:400px;" id="box-map">
                                <?= $this->render('ymap.php', ['accountModel'=>$model->account] );?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>

<?php

$js = <<< JS

$('#rent-form').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);
        if(form.find('.has-error').length) {
                return false;
        }

        var form = $('#rent-form');
        var days = $('.res_brone_date').val().replace(/,/g, "<br/>");

        $('#reserveDates').html(days);
        $('#divDisabled').show(800);

        return false;
});

$('#del-success').on('click', function(){
     $('#divDisabled').hide(800);
     return false;
});

$('#add-success').on('click', function(e){

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

JS;


$this->registerJs($js);
?>
