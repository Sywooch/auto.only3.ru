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

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->title) ?></title>
    <?php $this->head() ?>
  
</head>
<body>
<?php $this->beginBody() ?>

   
    <div class="container-only" style="width: 700px;">
       
                <?= $content ?>
         
    </div>




<?php $this->endBody() ?>
 
</body>
</html>
<?php $this->endPage() ?>
