<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="site-error">


    <?php if($exception->statusCode == '404') { ?>
        <div class="message404 text-center">
            <h1 style="font-size: 180px; font-weight: bold;">404</h1>
        </div>
    <? } else {?>

        <h1><?= Html::encode($this->title) ?></h1>

    <?}?>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>  <a href="<?=Url::home();?>"><br/>Перейти на главную</a>.
    </div>




</div>
