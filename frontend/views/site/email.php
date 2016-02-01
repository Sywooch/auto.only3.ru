<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\ContactForm;

$this->title = 'Email-подписка';
$this->registerCssFile('/css/site-index.css');
?>


<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

 
    <div class="info" style='text-align:center;'>
      На ваш почтовый ящик пришло письмо с подтверждением рассылки.
  <br>
Подтвердите, что согласны получать новости о системе онлайн резервирования авто auto.only3.ru!
    </div>

</div>

<div style="clear:both;"></div>

</div>
