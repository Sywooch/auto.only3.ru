<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use yii\web\JsExpression;
use yii\jui\AutoComplete;

use frontend\models\System;

use frontend\modules\account\Module;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" href="/favicon.ico?v=1" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,900&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->title) ?></title>
    <?php $this->head() ?>
  
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <header>
        <?php /*
         <div class="headNav">
            <div class="container">
                <div class="row navtop">
                    <div class="col-xs-10 no-offset">
                        <?=$this->render('_headerModal');?>

                        <?php
                        if(!empty($this->params['topMenuItems'])) {
                            $menuItems = $this->params['topMenuItems'];

                            NavBar::begin([
                                'brandUrl' => Yii::$app->homeUrl,
                                'options' => [
                                    'class' => 'navbar-inverse navbar-top',
                                ],
                            ]);

                            if (Yii::$app->user->isGuest) {
                                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                            } else {
                                $menuItems[] = [
                                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                                    'url' => ['/site/logout'],
                                    'linkOptions' => ['data-method' => 'post']
                                ];
                            }
                            echo Nav::widget([
                                'options' => ['class' => 'navbar-nav navbar-center'],
                                'items' => $menuItems,
                            ]);
                            NavBar::end();
                        }
                        ?>
                    </div>
                    <div class="col-xs-2 pull-right">
                      </div>
                </div>
            </div>
        </div>
        */?>
        <div class="container logo-container">
            <div class="row">
                <div class="col-xs-4 logo">
                    <a href="http://only3.ru/">
                        <img class="logo" src="/images/logo.png" alt="auto.Only3.ru" title="auto.Only3.ru" />
                    </a>
                    <a href="<?=Url::toRoute(['/site/index','city_url'=>$this->context->city_url])?>" class="a-to-auto">Автопрокат</a>
                </div>
                <div class="col-xs-6" id="box-select-city">
                    <?php /*
                    <div id="box-poisk-top">
                        <span>Найти авто </span>
                        <?php $form = ActiveForm::begin([
                            'action' => ['/'],
                            'method' => 'get',
                        ]); ?>

                            <?php

                            echo AutoComplete::widget([
                                'name' => 'AutoSearch[name]',
                                'id' => 'inp-search2',
                                'value' => ArrayHelper::getValue(Yii::$app->request->queryParams, 'AutoSearch.name'),
                                'clientOptions' => [
                                    'source' => new JsExpression("function(request, response) {
                                        $.getJSON('" . Url::toRoute('site/get-autos') . "', {
                                            name: $('#inp-search2').val()
                                        }, response);
                                    }"),
                                    'autoFill'=>true,
                                    'minLength'=>'2',
                                    'select' => new JsExpression("function( event, ui ) {
                                                $('#auto-search-name').val(ui.item.name);
                                     }")],
                                ]);

                            ?>
                            <input type="submit" id="sub-search2" value="Найти">
                        <?php ActiveForm::end()?>
                    </div> */?>

                   <div id='select-city'> Выберите город: </div>
                        <?=$this->render('_headerModal');?>

                        <?php
                        if(!empty($this->params['topMenuItems'])) {
                            $menuItems = $this->params['topMenuItems'];

                            NavBar::begin([
                                'brandUrl' => Yii::$app->homeUrl,
                                'options' => [
                                    'class' => 'navbar-inverse navbar-top',
                                ],
                            ]);

                            if (Yii::$app->user->isGuest) {
                                $menuItems[] = ['label' => 'Signup', 'url' => Module::URL_ROUTE_SIGNUP];
                                $menuItems[] = ['label' => 'Login', 'url' => Module::URL_ROUTE_LOGIN];
                            } else {
                                $menuItems[] = [
                                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                                    'url' => [Module::URL_ROUTE_LOGOUT],
                                    'linkOptions' => ['data-method' => 'post']
                                ];
                            }
                            echo Nav::widget([
                                'options' => ['class' => 'navbar-nav navbar-center'],
                                'items' => $menuItems,
                            ]);
                            NavBar::end();
                        }
                        ?>


                </div>
                <div class="col-xs-2 no-offset center">
                    <?php
                    if(Yii::$app->user->isGuest){ ?>
                        <a class='a-reg' href="<?= Url::toRoute([Module::URL_ROUTE_SIGNUP]);?>">Регистрация</a>
                    <?php } else {
                        echo "<span class='userName'> " . Yii::$app->user->identity->username ."</span> <span class='userLogout'> " . Html::a('выход',[Module::URL_ROUTE_LOGOUT],['data-method' => 'post']) ."</span>";
                    } ?>

                    <?php if(Yii::$app->user->can('salon') or Yii::$app->user->isGuest): ?>
                        <div class='box-log'>
                            <a class='a-log' href="<?php echo Url::toRoute(Yii::$app->user->isGuest ? Module::URL_ROUTE_LOGIN : '/profile/system/index'); ?>">Личный кабинет</a>
                        </div>
                    <?endif?>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 main-content">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<footer>
    <div>
        <div class='footer-left-p'>
            <a target='_blank' href="http://forauto.only3.ru/">Прокатчикам</a>
            <a href="<?= Url::toRoute([Module::URL_ROUTE_LOGIN])?>">Личный кабинет</a>
            <a href="<?= Url::toRoute([Module::URL_ROUTE_SIGNUP])?>">Регистрация</a>
        </div>
        <div class='footer-left-p'>
            <a href="<?= Url::toRoute(['/site/contactus'])?>">Обратная связь</a>
        </div>
        <div class='footer-right-p'>
            Служба поддержки<span>support@only3.ru</span>
        </div>
        <div class='clear'></div>
        <div id='bottom-right'>© Любое копирование и использование материалов возможно только с разрешения правообладателя</div>
    </div>
</footer>

<?php $this->endBody() ?>
 <?php if(Yii::$app->session->hasFlash('login')){  ?>
  <script type="text/javascript">
    $(window).load(function() { yaCounter32280994.reachGoal('INPUT'); return true; });
  </script>
 <?php } ?>

 <?php if(Yii::$app->session->hasFlash('register')){ ?>
  <script type="text/javascript">
    $(window).load(function() { yaCounter32280994.reachGoal('REG'); return true; });
  </script>
 <?php } ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter32280994 = new Ya.Metrika({id:32280994,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/32280994" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
<?php $this->endPage() ?>