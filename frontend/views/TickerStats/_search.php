<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TickerStatsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticker-stats-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ticker') ?>

    <?= $form->field($model, 'short_ratio') ?>

    <?= $form->field($model, 'short_percent_of_float') ?>

    <?= $form->field($model, 'ebitda') ?>

    <?= $form->field($model, 'total_cash') ?>

    <?= $form->field($model, 'total_debt') ?>

    <?= $form->field($model, 'operating_cash_flow') ?>

    <?= $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
