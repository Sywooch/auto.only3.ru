<?
/*
$this->registerCssFile("/css/ion.rangeSlider.skinFlat.css");
$this->registerCssFile("/css/ion.rangeSlider.css");

$this->registerJsFile('/js/ion.rangeSlider.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
*/

use Yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\html;

use frontend\components\widgets\date\DatePicker;

$this->registerCssFile('/css/_filtersBlock.css');

/******заменить на DatePickerAsset asset***************/
$this->registerJsFile('/js/date/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/date/datepicker-kv.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
/******заменить на DatePickerAsset asset***************/

?>
<div id="filterRight">

    <?php if(isset($_GET['AutoSearch']['category'])) { ?>
        <h1>Найти авто в <a href="#modal" data-target="#modal-city-content" data-toggle="modal" class="to-ct"><?=$this->context->city_padezh?></a></h1>
    <?php } else { ?>
        <h2 id="h2-no-homepage">Найти авто в <a href="#modal" data-target="#modal-city-content" data-toggle="modal" class="to-ct"><?=$this->context->city_padezh?></a></h2>
    <?php } ?>

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['site/index','city_url' => $model->city_url]),
        'method' => 'get',
    ]); ?>

        <!--
            <div class="search_box1">
                <h2>Цена</h2>
                <div style="float:left;    width: 200px;">
                    <? //$form->field($model, 'min_cost',['template'=>'{input}'])->textInput(['id'=>'min_cost']); ?>
               </div>
                <div style="clear:both;"></div>
            </div>
-->

            <div class="search_box1">
            </div>

            <div class="search_box2">
                <div class='label-param'>Коробка</div>
                <div class="search_box2_2">
                    <div style="width: 70px;float: left;">
                        <?= $form->field($model, 'trans1',['template'=>'{input}'])->checkbox(['label'=>'МКПП', 'uncheck' => null]); ?>
                    </div>

                    <div style="width: 85px;float: left;">
                        <?= $form->field($model, 'trans2',['template'=>'{input}'])->checkbox(['label'=>'АКПП', 'uncheck' => null]); ?>
                    </div>
                      <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>
            </div>

            <div class="search_box3">
                <div>
                    <?= $form->field($model, 'conditioner')->checkbox(['uncheck' => null]); ?>
                </div>

                <div>
                    <?= $form->field($model, 'w_driver', ['template'=>'{input}'])->checkbox(['uncheck' => null,'label'=>'С водителем']); ?>
                </div>
            </div>

            <div class="row text-center" style="clear:both; width: 250px;margin: auto;">

                <?
                /*
                $form->field($model, 'day', ['template'=>'{input}'])->widget(DatePicker::classname(), [
                        'name' => 'day',
                        'type' => DatePicker::TYPE_INLINE,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                        ],
                        'options' => [
                            'class' => 'hide'
                            // you can hide the input by setting the following
                            // 'class' => 'hide'
                        ]
                ])
                */
                ?>
                <?php

                $date = new DateTime('now');
                $date->modify('+2 month');
                $date->modify('last day of this month');
                $date->format('Y-m-d');

                    echo '<div class="" id="datePick">';
                        echo DatePicker::widget([
                            'name' => 'AutoSearch[day]',
                            'type' => DatePicker::TYPE_INLINE,
                            'value' => $model->day,
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                                'convertFormat' => true,
                                'toggleActive' => false,
                                'multidate' => false,
                                'minViewMode' => 0,
                                'maxViewMode' => 0,
                                'startView' => 0,
                                'startDate' => date('Y-m-d'),
                                'endDate' => $date->format('Y-m-d'),
                            ],
                            'pluginEvents' => [
                              //  "show" => "function(e) {  alert('test'); }",
                            ],
                            'options' => [
                                'class' => 'hide',
                            ]
                        ]);
                     echo '</div>';
                ?>
            </div>
            <div style="text-align:center; margin-bottom: 15px;">
                <input type='submit' class='btt' value="Поиск" style="width: 160px;" />
            </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
/*
$js = <<< JS

  $('document').ready(function(){

      $('#min_cost').ionRangeSlider({
        min: 100,                        // минимальное значение
        max: 20000,                       // максимальное значение
        from: {$model->min_price},                 // предустановленное значение ОТ
        to: {$model->max_price},                   // предустановленное значение ДО
        type: 'double',                 // тип слайдера
        step: 500,                      // шаг слайдера
        postfix: 'руб',                // постфикс значение
        onChange: function(obj){        // callback функция, вызывается при изменении состояния

          },
      });
    });

JS;

$this->registerJs($js, View::POS_END);
*/

//$this->registerJs($js);

?>


