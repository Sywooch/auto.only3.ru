<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <div class="row" style="margin-top:10px;margin-bottom:10px;">
        <div class="col-xs-6 text-left"><div style="float: left;
  font-family: 'Chewy';
  font-size: 40px;
  line-height: 33px;
  color: #D6D6D6;">
                <a href="/admin/"><img style="height: 50px;" src="/images/logo.png"><span style="color: #A8A8A8;
  font-family: 'Open Sans';
  font-size: 16px;
  display: inline-block;
  margin-left: 12px;
  vertical-align: top;
  margin-top: 10px;">CRM</a></span>
            </div></div>
        <div class="col-xs-6 text-right"><?php if(!Yii::$app->user->isGuest):?> <?=Html::a('Выход',['site/logout'], ['class'=>'btn btn-danger', 'data-method' => 'post']);?><?endif?></div>
    </div>

    <div class="row">
        <div class="col-xs-12" style="margin-top:20px;"></div>
    </div>

</div>

    <?php
    /*
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();

    */
    ?>

<div class="container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?php if(!Yii::$app->user->isGuest):?>
        <?=$this->render('_head');?>
    <?endif?>
        <?= $content ?>
</div>


<footer class="footer2">
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
