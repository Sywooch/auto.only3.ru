<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\profile\models\SystemAuto */

$this->title = 'Create System Auto';
$this->params['breadcrumbs'][] = ['label' => 'System Autos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-auto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>