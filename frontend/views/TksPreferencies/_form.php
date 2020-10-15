<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TksPreferencies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tks-preferencies-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PreferenceName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Max')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>