<?php
//классы стилей добавлять в frontend/web/css/our-cars.css

//$accountModel->name Название аккаунта
//$accountModel->phone телефон
//$accountModel->url  - ссылка на сайт
//$accountModel->address - адрес салона
//$accountModel->balance - баланс

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;
use yii\web\View;

$this->registerCssFile('/css/ourCars.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'position' => View::POS_END
]);

$Punycode = new \idna_convert(array('idn_version' => 2008));
$siteUrl = parse_url($accountModel->url);

if(isset($siteUrl['host'])) {
    $siteUrl['host'] = $Punycode->decode($siteUrl['host']);
    $siteUrl = Html::encode($siteUrl['scheme'].'://'.$siteUrl['host']);
} else {
    $siteUrl = Html::encode($accountModel->url);
}

?>

<div class="container system-our-cars">
    <div class="row" style="margin-bottom:20px;">
        <div class="col-xs-12">
            <?=$this->render('_breadscrumb2',['model'=>$accountModel]);  ?>
        </div>
    </div>

 <!--   <div class="row">
        <div class="col-xs-12">
            <?=$this->render('_social'); ?>
        </div>
    </div>  -->

    <div class="row">
        <div class="col-xs-7 no-offset">
            <div class="row no-offset">
                <div class="col-xs-8 mm-name no-offset">Автопрокат <?=$accountModel->username?></div>
                <div class="col-xs-4 text-right "></div>
            </div>
            <div class="cars-autoCars-index">
                <?= ListView::widget([
                    'emptyText' => 'Список автомобилей пуст',
                    'dataProvider' => $dataProvider,
                    'layout' => '{items}',
                    'options' => ['tag'=>'ul', 'class'=>'items'],
                    'itemOptions' => ['class' => '', 'tag'=>'li'],
                    'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_itemListAuto', ['model' => $model]);
                    },
                ]) ?>
            </div>
        </div>

        <div class="col-xs-5">

         <!--   <div class="adress-short">
                <?=$accountModel->city_name?>., Столярный переулок, 16<span class="pop-map">скрыть карту</span>
            </div> -->

            <div id="contact-text">
              <h2>Наши контакты</h2>
               <div class='item-contact'>
                   <span class='con-label'>Телефон</span>
                   <span class='con-val'><?=$accountModel->phone?></span>
               </div>

                <? if(!empty($accountModel->url)):?>
                    <div class='item-contact'>
                        <span class='con-label'>Сайт</span>
                        <span class='con-val'><a href="<?=Html::encode($accountModel->url)?>" target="_blank"><?=$siteUrl?></a></span>
                    </div>
                <?endif?>

                <div class='item-contact'>
                   <span class='con-label'> Адрес</span>
                   <span class='con-val'><?=$accountModel->city_name?>
                   <?php if($accountModel->address!=''){ ?>
                     <?=', '.$accountModel->address?>
                   <?php } ?> 
                   </span>  
               </div>

                <?php if($accountModel->place_delivery!=''){ ?>
               <div class='item-contact'>
                   <span class='con-label'> Режим работы</span>
                   <span class='con-val'><?=$accountModel->place_delivery?></span>  
               </div>
              <?php } ?>  
              <?php if($accountModel->other!=''){ ?>
               <div class='item-contact'>
                 
                   <span class='con-val'><?=$accountModel->other?></span>  
               </div>
              <?php } ?>  
            </div>
         
            <?= $this->render('ymap.php', ['accountModel'=>$accountModel] );?>

            <div id="soc-buttons" >

                <h2>Расскажите друзьям</h2>


<script type="text/javascript">(function(w,doc) {
if (!w.__utlWdgt ) {
    w.__utlWdgt = true;
    var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
}})(window,document);
</script>
<div data-background-alpha="0.0" data-buttons-color="#ffffff" data-counter-background-color="#ffffff" data-share-counter-size="13" data-top-button="false" data-share-counter-type="separate" data-share-style="10" data-mode="share" data-like-text-enable="false" data-hover-effect="scale" data-mobile-view="false" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#ffffff" data-share-shape="round-rectangle" data-sn-ids="fb.vk.ok.mr." data-share-size="40" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-pid="1413007" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="false" data-selection-enable="false" class="uptolike-buttons" ></div>
            </div>

        </div>

    </div>
</div>


<?php

$js = <<< JS

$(document).ready(function($) {
      $('.pop-map').click(function(){  $('#map').toggle();  });
});

JS;

$this->registerJs($js, View::POS_END);

?>
