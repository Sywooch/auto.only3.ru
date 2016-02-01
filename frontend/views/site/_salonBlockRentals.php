<?php
/**
 * Created by Alex Semenov hejvkt@yandex.ru.
 * Author: Alex Semenov
 * Date: 25.12.2015
 * Time: 16:41
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsFile('http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU');

?>
<div id="salon-box">
  <div class='box-image-rental'>
    <h1><?='Все прокаты в '.$this->context->city_padezh?></h1>
  </div>  
  <?= $this->render('_categoryBlockTest'); ?>
    <ul class='list-salons list-b2'>
        <?php foreach($salonModels as $salon):?>
            <li>
                <h3><?=Html::a(Html::encode($salon->username), $salon->UrlSalon)?></h3>
                <div class="salon-phone"><?=(Html::encode($salon->phone))?></div>
                <div class="salon-adress"><?=(Html::encode($salon->address))?>&nbsp;</div>
            </li>
        <?endforeach?>
    </ul>
</div>

<div style="text-align: left;"><span id="button-red" class="button-red-red" style="display:none;">Скрыть карту</span></div>
<div id="mpd"><span>Показать на карте</span></div>
<div id="map" ></div>
<p></p>
<?php

$Models= $salonModels;

$zoom = 13;
$xy = $this->context->xy;

if($Models)
    $xy = $Models[0]->xy ?  $Models[0]->xy : $xy;

$js = "

    var myMap;
    var myGeoObjects = [];

   ymaps.ready(function(){

    myMap = new ymaps.Map('map', {
            center: [".$xy."],
            zoom: ".$zoom."
        },
        {
          minZoom: 9,
          maxZoom:16
        }
    );

    myMap.setType('yandex#map');
  //myMap.behaviors.enable('scrollZoom');
    myMap.behaviors.enable('drag');
          myMap.controls
        .add('zoomControl', { left: 5, top: 5 })
        .add('typeSelector')
        .add('mapTools', { left: 35, top: 5 });
";


$i=-1;
foreach($salonModels as $salon){
    foreach($salon->systemAutos as $model) {

        $i++;
        $URL = Url::toRoute(['cars/reserve-a-car', 'id' => $model->id]);
        $otz = 1;

        $xy = $model->account->xy;

        if (empty($xy)) {
            $xy = "81.115328,95.694906";
        }

        $info = "
        <div class=ya_box>
            <div class=ya_box_left>
                <a target=blank href=\'" . $URL . "\'><img class=ya_thumb src=\'" . $model->photo . "\' /></a></div>
                <div class=ya_box_right>
                <div class=p_name><a target=blank href=\'" . $URL . "\'>" . $model->name . "</a></div>
                <div class=p_type>" . $model->account->username . "</div>
                    <a class=bty target=blank href=\'" . $URL . "\'>Забронировать</a></div>
                <div style=clear:both></div>

            </div>";

        $js .= "\n myGeoObjects[" . $i . "] = new ymaps.GeoObject({
          geometry:
       {
        type: 'Point',
        coordinates: [" . $xy . "],

       },
       properties:
       {
        clusterCaption: '" . $model->account->address . "',
        balloonContentBody: '" . $content = str_replace(array("\r\n","\n"), '', $info) . "'
       }
     });  ";

        $js .= "myGeoObjects[" . $i . "].options.set('iconImageHref', '/images/iconmap/key.png'); ";
        $js .= "myGeoObjects[" . $i . "].options.set('iconImageSize', [32, 48]); myGeoObjects[" . $i . "].options.set('iconImageOffset', [-15, -47]);";
    }
}

$js .=  "\n ClusterBalloonContentItemLayout = ymaps.templateLayoutFactory.createClass([
            '<div class=entry>',
            '<div class=bold>$[properties.balloonContentHeader]</div>',
            '<div>$[properties.balloonContentBody]</div>',
            '<div class=author>$[properties.balloonContentFooter]</div>',
            '</div>'
        ].join(''));

    var myClusterer = new ymaps.Clusterer({

            clusterBalloonContentBodyLayout: 'cluster#balloonCarouselContent',

            clusterBalloonContentItemLayout: ClusterBalloonContentItemLayout,

            clusterBalloonPagerSize: 5,

            clusterBalloonWidth: 370,
      clusterBalloonHeight: 245
        });

    myClusterer.add(myGeoObjects);

    myClusterer.options.set({
         zoomMargin: 90,
     margin:15
    });

    myMap.geoObjects.add(myClusterer);
});



$('#button-red').click(function(){

  if($('#map').is(':visible')){
   $('#mpd').show();
  $('#map').hide();
  $('#button-red').hide();
  }
  else{
  $('#mpd').hide();
  $('#button-red').show();
  $('#map').show();
  }


  });

  $('#mpd').click(function(){
      $('#mpd').hide();
      $('#button-red').show();
      $('#map').show();
  });

$('#button-red').trigger('click');

";

$this->registerJs($js);

?>