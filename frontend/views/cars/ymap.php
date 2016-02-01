<?php
use yii\web\View;
use frontend\models\City;
use yii\helpers\Html;
/*карта*/

$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU');

if(!empty($accountModel->xy)) {

        $coord = $accountModel->xy;

    } else {
        $city = City::find()->where(['name'=>$accountModel->city_name])->one();
        $coord = $city->xy;
       
    }

    echo Html::tag('div',' ', ['id' => "map"]);

    $jsData = "
        var coord = [ " . $coord . "];
        var descr = '" . $accountModel->username . "';
        var street_d = '" . $accountModel->address . "';
        var profileImg = '';
    ";

    if (!empty($accountModel->thumb)) {
        $jsData .= " profileImg = '<img style=\"width: 80px;border: 1px solid #CACACA;padding: 2px;\" src=\"" . $accountModel->thumb . "\" />';";
    }

    $jsMap = <<< JS

    var myMap;

    // Дождёмся загрузки API и готовности DOM.
    ymaps.ready(init);

    function init () {

        var myMap,
            myPlacemark;

        myMap = new ymaps.Map('map', {
            center: coord,
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        });

        myPlacemark = new ymaps.Placemark(coord, {
            content: 'Москва!',
            balloonContent: 'Столица России'
            }
        );

        myMap.balloon.open(coord, "<div class='bal-box'><div>"+ profileImg +"</div>"+ descr +" <p>"+ street_d +"</p></div>", {
            // Опция: не показываем кнопку закрытия.
            closeButton: false
        });

        myMap.geoObjects.add(myPlacemark);
    }


JS;

    $this->registerJs($jsData, View::POS_END);
    $this->registerJs($jsMap, View::POS_END);

 // } else {

 //   echo Html::tag('div','Адрес салона не задан', ['id' => "map"]);


//}
?>