<?php

use yii\helpers\Html;
use yii\widgets\ListView;

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бронирование аренды авто в 3 клика';

$this->registerCssFile('/css/site-index.css', ['depends' => [kartik\date\DatePickerAsset::className()]]);
$this->registerJsFile('http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU');

?>

 <?php if(!isset($_GET['AutoSearch']['category'])) { ?>
 <div id="back-photo">
            <h1>Аренда автомобилей в <?=$this->context->city_padezh?></h1>
  <ul id="tezis">
       <li  id="tezis1">Онлайн бронирование автомобилей</li>
       <li  id="tezis2">Открытая история бронирования</li>
       <li  id="tezis3">Лучшие прокатчики вашего города</li>
       <li  id="tezis4">Удобный поиск и оформление машин</li>
  </ul>
 </div>
 <?php } ?>

<?= $this->render('_categoryBlockTest'); ?>

<div class="row">
    <div class="col-xs-8">
        <div id="salon-box">
          <h2><a  href="#">Салоны автопроката в <?=$this->context->city_padezh?></a></h2>
          <ul>
            <li>
              <h3><a href="#">Комильфо</a></h3>
              <div class='salon-phone'>8(8332)21-07-05</div>
              <div class='salon-adress'>Герцена, 58</div>
            </li>
            <li>
              <h3><a href="#">Фаворит авто</a></h3>
              <div class='salon-phone'>+7(922)668-34-39</div>
              <div class='salon-adress'>Ленина, 20</div>
            </li>
            <li>
              <h3><a href="#">Rolls Royce</a></h3>
              <div class='salon-phone'>8(8332)21-07-05</div>
              <div class='salon-adress'>Воровского, 133</div>
            </li>
            <li>
              <h3><a href="#">Комильфо</a></h3>
              <div class='salon-phone'>8(8332)21-07-05</div>
              <div class='salon-adress'>Герцена, 58</div>
            </li>
          </ul> 
        </div>
        <div style="text-align: left;"><span id="button-red" class="button-red-red" style="display:none;">Скрыть карту</span></div>
        <div id="mpd"><span>Показать на карте</span></div>
        <div id="map" ></div>
        <div class="system-auto-index">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}<br/>{pager}',
                'options' => ['tag'=>'ul', 'class'=>'items'],
                'itemOptions' => ['class' => '', 'tag'=>'li'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_itemListAuto', ['model' => $model]);
                },
            ]);
            ?>

        </div>
    </div>
    <div class="col-xs-4">
        <?= $this->render('_filtersBlock', ['model'=>$searchModel]);?>
    </div>
</div>


<?php

$Models= $dataProvider->getModels();

$zoom = 12;
$xy = $this->context->xy;

if($Models)
    $xy = $Models[0]->account->xy ?  $Models[0]->account->xy : $xy;

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
    foreach($Models as $model){
        $i++;
        $URL = Url::toRoute(['cars/reserve-a-car', 'id'=>$model->id]);
        $otz = 1;

        $xy = $model->account->xy;

        if(empty($xy)){
            $xy = "81.115328,95.694906";
        }

        $info ="
        <div class=ya_box>
            <div class=ya_box_left>
                <a target=blank href=\'".$URL."\'><img class=ya_thumb src=\'".$model->photo."\' /></a></div>
                <div class=ya_box_right>
                <div class=p_name><a target=blank href=\'".$URL."\'>".$model->name."</a></div>
                <div class=p_type>".$model->account->username."</div>
                    <a class=bty target=blank href=\'".$URL."\'>Забронировать</a></div>
                <div style=clear:both></div>
                
            </div>";

        $js .= "\n myGeoObjects[".$i."] = new ymaps.GeoObject({
          geometry:
       {
        type: 'Point',
        coordinates: [".$xy."],

       },
       properties:
       {
        clusterCaption: '".$model->account->address."',
        balloonContentBody: '".$content = str_replace("\n", '', $info)."',

       }
     });  ";

        $js .= "myGeoObjects[".$i."].options.set('iconImageHref', '/images/iconmap/key.png'); ";
        $js .= "myGeoObjects[".$i."].options.set('iconImageSize', [32, 48]); myGeoObjects[".$i."].options.set('iconImageOffset', [-15, -47]);";

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

";

$this->registerJs($js);

?>