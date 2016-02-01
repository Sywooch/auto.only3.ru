<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\ContactForm;

$this->title = 'Спасибо за подписку';
$this->registerCssFile('/css/site-index.css');
?>


<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

 
    <div class="info" style='text-align:center;'>
     Ваш адрес подтверждён и рассылка на него успешно активирована.
    </div>

</div>

<div style="clear:both;"></div>

</div>
