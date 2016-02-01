<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\profile\models\SystemAuto */

$this->title = 'Update System Auto: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'System Autos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="system-auto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>