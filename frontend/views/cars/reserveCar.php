<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\DetailView;

use frontend\modules\profile\models\Rentact;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Json;

use yii\bootstrap\Alert;

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

?>

<div class="system-auto-view">
    <div class="autoItemView container">

        <?=$this->render('_breadscrumb',['model'=>$systemModel]); ?>

        <?php /*$this->render('_social'); */ ?>
        

        <div class="row" style="margin-top:20px;">
            <div class="col-xs-3" id="left-boxer">

                <ul id="list-photo-des">
                    <?php
                        echo '<li><a class="gallery" href="'.$systemModel->getThumbImage($systemModel->photo, 1000, 1000).'">';
                            echo html::img($systemModel->getThumbImage($systemModel->photo, 210, 158), ['width' => '210px']);
                        echo '</a></li>';
                    ?>

                    <?php
                        foreach($systemModel->PhotosUrl as $photoUrl){
                            echo '<li><a class="gallery" href="'.$photoUrl.'">';
                                echo html::img($systemModel->getThumbImage($photoUrl, 210, 158), ['width' => '210px']);
                            echo '</a></li>';
                        }
                    ?>
                </ul>
            </div>

            <div class="col-xs-9">
                <div class="row">
                    <?
                        //TODO сюда засунуть алерты
                    ?>

                    <div class="col-xs-12">
                        <div class="phone-img">
                    <span id="number-phone-img" class="phone-service"><?=$systemModel->account->phone?></span>
                        </div>

                        <div class="mm-name"><?=html::encode($systemModel->name)?><?=($systemModel->year ? ", ".$systemModel->year:"")?></div>
                        <div class="mm-cat"><?=$systemModel->getCategoryList($systemModel->category);?></div>
                        <p class="mm-other"><?=html::encode($systemModel->info)?></p>

                        <div class="shadow2" style="padding-bottom: 5px;">
                            <div class="shadow2-1">
                                <span>Цены</span>
                                <span style="margin-left: 18px; display: inline-block; text-align: center; width: 200px;">Характеристики</span>
                                <span style="display: inline-block;text-align: center; width: 200px;">Условия</span>
                            </div>
                            <div style="clear:both;"></div>

                            <div style="float:left;width:210px;" class="it-33">
                                <div class="vls">
                                    <?=$systemModel->cost1 ? "<span>".$systemModel->cost1." <span style='font-size: 14px;font-weight: normal;' class='rubl'>Р</span></span> &nbsp;&nbsp;&nbsp;на 1 сутки":"";?>
                                </div>
                                <div class="vls">
                                    <?=$systemModel->cost2 ? "<span>".$systemModel->cost2." <span style='font-size: 14px;font-weight: normal;' class='rubl'>Р</span></span> &nbsp;&nbsp;&nbsp;от 2 суток":"";?>
                                </div>
                                <div class="vls">
                                    <?=$systemModel->cost8 ? "<span>".$systemModel->cost8." <span style='font-size: 14px;font-weight: normal;' class='rubl'>Р</span></span> &nbsp;&nbsp;&nbsp;от 8 суток":"";?>
                                </div>
                            </div>


                            <div style="float:left;width:205px;">
                                <div class="val-param"><span class="vals">Мощность:</span> <span class="names"><?=$systemModel->power?>(л/с)</span></div>
                                <div class="val-param"><span class="vals">Коробка:</span> <span class="names"><?=$systemModel->trans == '1' ? "МКПП": "АКПП"?></span></div>
                                <div class="val-param"><span class="vals">Кондиционер:</span> <span class="names"><?=$systemModel->w_driver == 'Y' ? "нет": "есть"?></span></div>
                            </div>
                            <div style="float:left;width:230px;">

                                <div class="val-param"><span class="vals">Залог:</span> <span class="names"><?=$systemModel->pledge?> <span class='rubl'>Р</span></span></div>
                                <div class="val-param"><span class="vals">Предоставляется:</span> <span class="names"><?=$systemModel->w_driver == 'Y' ? "с водителем": "без водителя"?></span></div>

                            </div>
                            <div style="clear:both;"></div>
                        </div>

                    </div>
                </div>

                <?php if(!$systemModel->NotBeRented):?>
                <div class="row">
                    <div class="col-xs-12">

                        <div class="shadow2" style="position: relative;">

                            <?php

                            if($stepForm == 1){
                                echo $this->render('_reserve-step1',['rentModel'=>$rentModel, 'systemModel' => $systemModel]);
                            } elseif($stepForm == 2) {
                                echo $this->render('_reserve-step2',['rentModel'=>$rentModel, 'systemModel' => $systemModel, 'prepareModel' => $prepareModel, 'passwordFormModel' => $passwordFormModel]);
                            } elseif($stepForm == 'auth') {
                                echo $this->render('_reserve-step-auth',['rentModel'=>$rentModel, 'systemModel' => $systemModel, 'loginModel' => $loginModel]);
                            } else {
                                echo $this->render('_reserve-step3',['rentModel'=>$rentModel, 'systemModel' => $systemModel]);
                            }

                            ?>

                    </div>
                </div>
                <?php endif?>
<?php ?>
                <div class="row" id='bx-mp'>
                    <div class="col-xs-12 text-center">
                        <div class="shadow2">
                            <h2 style="margin: 10px 0px;font-size: 16px;"><span class="sp-map">Как к нам добраться</span></h2>

                            <div id="box-map">
                                <?= $this->render('ymap.php', ['accountModel'=>$systemModel->account] );?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 text-center">
                       <div class="shadow2" style="position: relative; margin-bottom: 10px;">
                             
                        <div id="left-social">
                             Расскажите друзьям
                        </div>

                        <div id="right-social">
                           
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
                        <div style="clear:both;"></div>
<!-- -->
<?php ?>
                       </div>
                    </div>
                </div>

            </div>
         </div>
    </div>
</div>

<?php

$jsInit = '';
$js = <<< JS

var nextStep = '';

/*
$('.del-success').on('click', function(){
     $(this).closest('.divDisabled').hide(800);
     return false;
});
*/
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
                $('#divStepData').hide(800);

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

/*********************************************************/
$("#Rentact_phone").mask("+7 (999) 999-99-99");
$("#Rentact_phone").on('change', function(ev){
    var parEl = $(this).parent();
    if($(parEl).hasClass('has-error')){
        $(parEl).removeClass('has-error');
    }
});
JS;

$this->registerJs($jsInit.$js);
?>
