<?php

use frontend\modules\profile\models\SystemAuto;
use yii\helpers\Url;

$categorys = SystemAuto::getCategoryList();

?>

<div class="row">
    <div class="col-md-12">
        <ul id="list-category">
            <?php foreach($categorys as $id => $name): ?>
            <?php $selectIcon = ''; $selectClass = ''; if(isset($_GET['AutoSearch']['category'])) { if($_GET['AutoSearch']['category']==$id) { $selectIcon = '2'; $selectClass = 'sel-img'; } } ?>
                <li style="width: 132px;vertical-align: top;">
                    <a href="<?=Url::toRoute(['site/index','AutoSearch[category]' => $id, 'city_url' => $_GET['city_url']])?>" class='<?=$selectClass?>'>
                        <div>
                            <img src='/images/auto<?=$id;?><?=$selectIcon?>.png' />
                        </div>
                        <span><?=$name;?></span>
                    </a>
                </li>
            <?endforeach?>
            <?php 
             $selectIcon2 = ''; $selectClass2 = ''; 
             if($this->context->action->id=='car-rentals'){ $selectClass2 = 'sel-img';  $selectIcon2 = '2'; } ?>

            <li style="width: 132px;vertical-align: top;">
                    <a href="<?=Url::toRoute(['/site/car-rentals','city_url' => $this->context->city_url])?>" class='<?=$selectClass2?>'>
                        <div>
                            <img src='/images/salon<?=$selectIcon2?>.png' />
                        </div>
                        <span>Все прокаты</span>
                    </a>
                </li>
        </ul>
    </div>
</div>