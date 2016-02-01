<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;

use frontend\models\City;
use frontend\models\Region;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\web\View;

$regions = Region::find()->orderBy(['name' => SORT_ASC])->all();
$i=0;
$countInCol = ceil(count($regions) / 3);

Modal::begin([
    
    'options' => [
        'id' => 'modal-city-content',
        'tabindex' => false // important for Select2 to work properly
    ],
    'toggleButton' => [
        'label' => $this->context->city . '<span class="caret"></span>',
        'tag' => 'div',
        'id' => 'modal-city',
    ],
    'header' => 'Выберите Ваш город'

]);

echo '<div class="row" style="width:300px;margin: 0px auto 20px;">';

$Citys = City::find()->orderBy('name')->asArray()->all();
$dataCity = ArrayHelper::map($Citys,'id','name');

$Citys = ArrayHelper::index($Citys, 'id');


$dataCity2 = ArrayHelper::map($Citys,'trans','name');

echo Select2::widget([
    'name' => 'city_id',
    'data' => $dataCity2,
    'options' => ['placeholder' => 'Выберите город ..'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);

 echo "</div>";

    echo '<div class="row">';
        echo "<div class='col-xs-4'>
                <ul>";

    echo '<li>
          <a href="'.Url::toRoute(['/site/index','city_url' => $Citys['1']['trans']]).'">'.$Citys['1']['name'].'</a>
    </li>
    <li>
          <a href="'.Url::toRoute(['/site/index','city_url' => $Citys['2']['trans']]).'">'.$Citys['2']['name'].'</a>
    </li>
    <li>
          <a href="'.Url::toRoute(['/site/index','city_url' => $Citys['4']['trans']]).'">'.$Citys['4']['name'].'</a>
    </li>
    <li style="margin-bottom: 21px;">
          <a href="'.Url::toRoute(['/site/index','city_url' => $Citys['3']['trans']]).'">'.$Citys['3']['name'].'</a>
    </li>';

    foreach($regions as $region){
        if($i == $countInCol){
            echo "</ul></div><div class='col-xs-4'><ul>";
            $i=0;
        }
        $i++;
        echo "<li>";
            echo $region->name;
        echo "<div>";
            foreach($region->cities as $city){
                echo html::tag('a', $city->name, ['href'=>Url::toRoute(['/site/index','city_url'=>$city->trans])]).' ';
            }
        echo "</div>";

        echo "</li>";

    }

        echo "</ul>";
    echo "</div>";
    Modal::end();

    echo "</div>";

$js = 'var urlBase = "'.Url::toRoute('/site/index').'";';

$js .= <<< JS

    $('.modal-body li').on('click', function(){
        $('.modal-body li').removeClass('bold'); 
         $('.modal-body li').find('div').hide();
        $(this).toggleClass('bold');
        $(this).find('div').toggle(100);
    });

    $('select[name="city_id"]').on('change', function(){
        var c = $(this).val();
        document.location.href=urlBase+c;
    });

JS;

$this->registerJs($js);
?>


