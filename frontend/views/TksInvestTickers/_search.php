<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TksInvestTickersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tks-invest-tickers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'figi') ?>

    <?= $form->field($model, 'ticker') ?>

    <?= $form->field($model, 'isin') ?>

    <?= $form->field($model, 'minPriceIncrement') ?>

    <?php // echo $form->field($model, 'lot') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
