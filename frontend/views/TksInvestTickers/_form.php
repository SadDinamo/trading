<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TksInvestTickers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tks-invest-tickers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'figi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticker')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'minPriceIncrement')->textInput() ?>

    <?= $form->field($model, 'lot')->textInput() ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
