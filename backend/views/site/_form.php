<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\profile\models\SystemAuto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-auto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList([ 6 => '6', 5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'cost1')->textInput() ?>

    <?= $form->field($model, 'cost2')->textInput() ?>

    <?= $form->field($model, 'cost8')->textInput() ?>

    <?= $form->field($model, 'min_cost')->textInput() ?>

    <?= $form->field($model, 'trans')->dropDownList([ 1 => '1', 2 => '2', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'conditioner')->dropDownList([ '0', '1', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'pledge')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contract')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photos')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'wheel')->dropDownList([ 1 => '1', 2 => '2', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'gear')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', '' => '', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'power')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fuel')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', 4 => '4', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>