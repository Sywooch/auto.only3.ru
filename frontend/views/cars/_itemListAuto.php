<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/*
<?=html::encode($model->getAttributeLabel('category'))?>
<?=html::encode($model->category)?>

<?=html::encode($model->cost1)?>
<?=html::encode($model->cost2)?>
<?=html::encode($model->cost8)?>
<?=html::encode($model->min_cost)?>
<?=html::encode($model->trans)?>
<?=html::encode($model->conditioner)?>
<?=html::encode($model->pledge)?>

<?=html::encode($model->info)?>
<?=html::encode($model->contract)?>

<?=html::encode($model->photo)?>
<?=html::encode($model->wheel)?>

<?=html::encode($model->gear)?>
<?=html::encode($model->number)?>

<?=html::encode($model->year)?>
<?=html::encode($model->power)?>
<?=html::encode($model->fuel)?>
*/

$conditioner = $model->conditioner == 1 ? html::img('/images/conditioner.png',['title'=> 'Кондиционер']) : '';
$trans = '';

if($model->trans){
    if($model->trans == '1'){
      //  $trans = html::img('/images/tr_m.png',['title'=> 'Ручная']);
        $trans = "<span class='trans-t'>МКПП</span>";
    } else {
     //   $trans = html::img('/images/tr_a.png',['title'=> 'Автомат']);
        $trans = "<span class='trans-t'>АКПП</span>";
    }
}

$urlReserve = $model->PageReserve;

$urlSalon = Url::toRoute(['/cars/our-cars', 'id' => $model->account_id]);
$urlSalon = Url::toRoute(['cars/our-cars', 'city_url' => $model->account->city->trans, 'slug_url' => $model->account->slug_url]);
?>

<div  class="it-1">
    <a href="<?=$urlReserve;?>"><?=html::img($model->getThumbImage($model->photo, 150, 150))?></a>
</div>

<div class='it-2'>
    <div style="position: relative;">
       <?php if($model->power){ ?> <div class="pwr"><?=html::encode($model->power)?> л/с</div> <?php } ?>
        <div class="it-2-icons">
            <?=$conditioner?>
            <?=$trans?>
        </div>
        <div class="it-2-head">
            <a href="<?=$urlReserve;?>"><?=html::encode($model->name)?>, <?=html::encode($model->year)?></a>
        </div>
        <div class="it-2-salon">
         
          <?php if($this->context->id == 'cars' && $this->context->action->id == 'our-cars') { echo "&nbsp;"; } else { ?>
          <a href="<?=$urlSalon;?>">Автопрокат <?=html::encode($model->account->username)?></a>
          <?php } ?>

        </div>
    </div>
    <div class="it-2-price">
        <div>
            <div><span class="days">на 1 сутки</span></div>
            <div class="it-2-dayPirce"><?=html::encode($model->cost1)?><span><span class='rubl'>Р</span>/день</span></div>
        </div>
        <div>
            <div><span class="days">от 2 суток</span></div>
            <div class="it-2-dayPirce"><?=html::encode($model->cost2)?><span><span class='rubl'>Р</span>/день</span></div>
        </div>
        <div>
            <div><span class="days">от 8 суток</span></div>
            <div class="it-2-dayPirce"><?=html::encode($model->cost8)?><span><span class='rubl'>Р</span>/день</span></div>
        </div>
    </div>

   <div class='mm-a-r'>
        <div class="mm-a-r-reserve">
            <a class="bty" href="<?=$urlReserve;?>">Бронировать</a>
        </div>
       <!--  <div class="mm-m-y-param">
           <div><span class='l-param'>Год:</span> <span class='v-param'><?=html::encode($model->year)?></span></div>
            <div><span class='l-param'>Мощность:</span> <span class='v-param'><?=html::encode($model->power)?> л/с</span></div> 
        </div> -->
        <div class="nma--r-pledge"><span class="difficult">Залог:</span>
            <span class="difficult-count">
              <?php if($model->pledge == NULL){ echo "без залога"; } else { ?>  
                <?=html::encode($model->pledge)?> <span class='rubl'>Р</span>
              <?php } ?>
            </span>
        </div>
    </div>
</div>
<div style="clear:both;"></div>
